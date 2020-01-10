'use strict';

// css
import './scss/styles.scss';

// js
import Vue from '../node_modules/vue/dist/vue.min';
import $ from 'jquery';
import mask from 'jquery-mask-plugin';
// import selectToLinks from './js/jquery-select-to-links';
// import owlCarousel from '../node_modules/owl.carousel/dist/owl.carousel.min';

// yii
import yii from '../vendor/yiisoft/yii2/assets/yii';
import yiiActiveForm from '../vendor/yiisoft/yii2/assets/yii.activeForm';
import yiiValidation from '../vendor/yiisoft/yii2/assets/yii.validation';
import bs from '../vendor/bower-asset/bootstrap/dist/js/bootstrap.min';

global.$ = global.jQuery = $;

// common
$(function () {
    const $body = $('body');
    $('input[type=tel]').mask('+7 (000) 000-00-00', {clearIfNotMatch: true});

    $('#open-menu').click(function (e) {
        e.preventDefault();

        if ($('.nav-container').css('display') != 'none') {
            $('.nav-container').slideUp();
        } else {
            $('.nav-container').slideDown();
        }

    });

    $('#close-menu').click(function (e) {
        e.preventDefault();

        $('.nav-container').slideUp();
    });

    $('.scroll-to').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top
            }, 500);
        }
    });

    // $('div.panorama').paver({tilt: false, failureMessage: '', gyroscopeThrottle: 1000/500, tiltSensitivity: 0.00001});

    // setTimeout(function () {
    //     let panoWidth = $("#pano-img").width();
    //     let documentWidth = $(document).width();
    //
    //     $(".paver--fallback").animate({
    //         scrollLeft: panoWidth/2 - documentWidth/2
    //     }, 1000);
    // }, 1000);

    setTimeout(function () {
        let pano = $('.panorama');
        let panoWidth = $('.panorama').width();
        let panoImg = $('#pano-img');
        let panoImgWidth = panoImg.width();

        $(window).on('resize', function (e) {
            panoWidth = $('.panorama').width();
            panoImgWidth = panoImg.width();
        });

        pano.scrollLeft( 0 );

        pano.on( "mousemove", function( e ) {
            let mouseX = e.pageX;

            let maxScrollLeft = (panoImgWidth / 2 - panoWidth / 2) * 2;

            let scrollLeft = mouseX * maxScrollLeft / panoWidth;

            pano.scrollLeft( scrollLeft );
        });

        setTimeout(function () {
            pano.animate({
                scrollLeft: panoImgWidth / 2 - panoWidth / 2
            }, 1000);
        }, 1000);

    }, 1000);

});

const osnsPage = document.getElementById('osns-penalty');
if (osnsPage) {
    new Vue({
        el: '#osns-penalty',
        data: {
            noOsns: true
        },
        methods: {
            toggleOsns(val) {
                this.noOsns = val

                return this.noOsns
            }
        }
    });
}

const osnsList = document.getElementById('osns-about-list');
if (osnsList) {
    new Vue({
        el: '#osns-about-list',
        data: {
            lists: {
                1: false,
                2: false,
                3: false
            }
        },
        methods: {
            toggleList(list) {
                this.lists[list] = !this.lists[list];

                return this.lists[list];
            }
        }
    });
}