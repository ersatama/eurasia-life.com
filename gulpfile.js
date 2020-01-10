'use strict';

//
// deploy options
//
const deployConfig = {
    folder: '/var/www/eurasia-life',
    ssh: {
        host: 'el.grafica.kz',
        username: 'grafica',
        port: 5622
    }
};

const deployProdFtpConfig = {
    host: 'eurasia-life.com',
    folder: '.',
    configFile: 'gulp/config.json'
};

//
// require
//
const gulp = require('gulp');
const GulpSSH = require('gulp-ssh');
const runSequence = require('run-sequence');
const webpackSteam = require('webpack-stream');
const fs = require('fs');
const fancyLog = require('fancy-log');
const ftp = require('vinyl-ftp');

// vars
const distPath = 'gulp/runtime/dist';

//
// Tasks
//

// build frontend
gulp.task('build', function () {
    process.env.NODE_ENV = 'production';
    return gulp.src('src/index.js')
        .pipe(webpackSteam(require('./webpack')))
        .pipe(gulp.dest(distPath));
});

// deploy backend: git pull, composer install, migrate/up
gulp.task('deploy:backend', function () {
    const dockerPhpCmd = 'docker exec -w=$(pwd) --user $(id -u):$(id -g) php ';
    return deploySshConnect(deployConfig)
        .shell([
            'cd ' + deployConfig.folder,
            'git pull',
            dockerPhpCmd + 'composer install --no-dev --prefer-dist --no-scripts --optimize-autoloader --classmap-authoritative --apcu-autoloader --quiet',
            // dockerPhpCmd + 'composer clearcache',
            dockerPhpCmd + 'php yii migrate --interactive=0',
            dockerPhpCmd + 'php yii cache/flush-schema --interactive=0',
        ], {filePath: 'deploy-main.log'})
        .pipe(gulp.dest('./gulp/runtime/logs'));
});

// deploy frontend: build frontend, upload frontend files
gulp.task('deploy:frontend', function () {
    return gulp.src(distPath + '/**')
        .pipe(deploySshConnect(deployConfig).dest(deployConfig.folder + '/' + distPath));
});

// деплой по ssh (тестовый, VPS)
gulp.task('deploy', function () {
    runSequence('build', 'deploy:backend', 'deploy:frontend');
});


// деплой фронта на хостинг
gulp.task('deploy-prod:frontend', function () {
    const localFolder = distPath,
        remoteFolder = deployProdFtpConfig.folder + '/' + distPath,
        conn = deployProductionFtpConnect();
    return gulp.src(localFolder + '/**', {base: localFolder, buffer: false})
        .pipe(conn.newerOrDifferentSize(remoteFolder))
        .pipe(conn.dest(remoteFolder));
});

// деплой
gulp.task('deploy-prod', function () {
    runSequence('build', 'deploy-prod:frontend');
});

// SSH deploy to test server
let _deploySshConnect;

function deploySshConnect(config) {
    return _deploySshConnect ? _deploySshConnect : (_deploySshConnect = new GulpSSH({
        ignoreErrors: false,
        sshConfig: {
            host: config.ssh.host,
            port: config.ssh.port ? config.ssh.port : 22,
            username: config.ssh.username,
            agent: process.env["SSH_AUTH_SOCK"]
        }
    }));
}

// FTP production server
let _deployProdFtpConnect;

function deployProductionFtpConnect() {
    return _deployProdFtpConnect ? _deployProdFtpConnect : (_deployProdFtpConnect = ftp.create({
        host: deployProdFtpConfig.host,
        user: localConfig().user,
        password: localConfig().password,
        log: fancyLog
    }));
}

// Local config
let _localConfig;

function localConfig() {
    return _localConfig ? _localConfig : (_localConfig = loadLocalConfigFile());
}

function loadLocalConfigFile() {
    const configFile = deployProdFtpConfig.configFile;
    if (!fs.existsSync(configFile)) {
        console.log('Error: Config file `' + configFile + '` not found');
        process.exit(1);
    }
    return JSON.parse(fs.readFileSync(configFile));
}
