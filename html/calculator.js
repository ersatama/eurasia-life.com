import Vue from '../node_modules/vue/dist/vue';
import VueMask from '../node_modules/v-mask/dist/v-mask'
// import Multiselect from 'vue-multiselect'
// import 'vue-multiselect/dist/vue-multiselect.min.css'

import axios from '../node_modules/axios/dist/axios.min.js'

import kprJson from './js/kpr';

Vue.use(VueMask);



;(function () {

    if( !window.i18n ) {
        window.i18n = {};
    }

    const kprToTariff = {
        1: "0.12",
        2: "0.29",
        3: "0.48",
        4: "0.49",
        5: "0.52",
        6: "0.53",
        7: "0.54",
        8: "0.65",
        9: "0.56",
        10: "0.88",
        11: "0.75",
        12: "0.76",
        13: "1.29",
        14: "1.55",
        15: "1.13",
        16: "1.17",
        17: "1.21",
        18: "2.43",
        19: "1.75",
        20: "2.05",
        21: "2.54",
        22: "2.96"
    };

    const correctionFactorMatrix = [
        [3,     2,      1.75,   1,      1,      1],
        [3.4,	3.2,	3,	    2.5,	1.25,	1.1],
        [3.8,	3.3,	3.2,	2.75,	2.4,	1.25],
        [4,     3.5,	3.3,	3,	    3.1,	1.5],
        [null,  3.6,	3.5,	3.4,	3,	    2],
        [null,  4,	    3.75,	3.5,	3.2,	3],
        [null,  null,   4,	    3.8,	3.6,	3.5]
    ];

    Vue.component('multiselect', window.VueMultiselect.default);


    Vue.component('calculator', {
        template: `<div class="calculator">
                        <h2>${ i18n.a1 }</h2>
                    
                        <div class="calculator__row">
                            <label for="kpr">${ i18n.a2 }</label>
                            <!--select id="kpr" v-model="kpr">
                                <option v-for="(position, key) in kprJson" :value="position.kpr+''+position.name" :key="key">{{ position.code }} - {{ position.name }}</option>
                            </select-->

                            <!--<v-select v-model="kprObj" :options="kprJson" label="name" value="kpr">-->
                                <!--<template slot="option" slot-scope="option">-->
                                    <!--{{ option.code }} - {{ option.name }}-->
                                <!--</template>-->
                            <!--</v-select>-->
                            <multiselect
                                placeholder="Выбирите вид деятельности"
                                v-model="kprObj"
                                :options="kprJson"
                                :show-labels="false"
                                :options-limit="10000"
                                :show-no-results="false"
                                label="name"
                                track-by="name"
                                :custom-label="nameWithCode">
                        </multiselect>
                        </div>
                        <div class="calculator__row">
                            <label for="more">${ i18n.a3 }</label>
                            <div class="d-inline-block position-relative">
                                <button @click="minusPayedMore" type="button" class="calculator__control calculator__control_minus">-</button>
                                <input v-model="payedMore" type="number" id="more" class="calculator__number" placeholder="0">
                                <button @click="plusPayedMore" type="button" class="calculator__control calculator__control_plus">+</button>
                            </div>
                        </div>
                        <div class="calculator__row text-nowrap">
                            <label class="small" for="more-fund">${ i18n.a4 }</label>
                            <input v-model.number="payedMoreFund" type="number" id="more-fund" class="calculator__fund" placeholder="0">
                            <span>тг</span>
                        </div>
                        <div class="calculator__row">
                            <label for="less">${ i18n.a5 }</label>
                            <div class="d-inline-block position-relative">
                                <button @click="minusPayedLess" type="button" class="calculator__control calculator__control_minus">-</button>
                                <input v-model="payedLess" type="number" id="less" class="calculator__number" placeholder="0">
                                <button @click="plusPayedLess" type="button" class="calculator__control calculator__control_plus">+</button>
                            </div>
                        </div>
                        <div class="calculator__row text-nowrap">
                            <label class="small" for="less-fund">${ i18n.a4 }</label>
                            <input v-model.number="payedLessFund" type="number" id="less-fund" class="calculator__fund" placeholder="0">
                            <span>тг</span>
                        </div>
                        <div class="calculator__row calculator__accidents">
                            <div class="row">
                                <div class="col-2 col-md-1 pr-0 text-center">
                                    <input type="checkbox" v-model="accidents" id="accidents">
                                </div>
                                <div class="col-10 col-md-11 pl-0">
                                    <label for="accidents">${ i18n.a6 }</label>
                                </div>
                            </div>
                            <div class="row pb-3" v-if="accidents">
                                <div class="col-2 col-md-1"></div>
                                <div class="col-10 col-md-11 mt-3 pl-0">
                                    <label for="accidentsNum" class="mb-2">${ i18n.a7 }</label>
                                    <div class="d-inline-block position-relative">
                                        <button @click="minusAccidentsNum" type="button" class="calculator__control calculator__control_minus">-</button>
                                        <input v-model="accidentsNum" type="number" id="accidentsNum" class="calculator__number" placeholder="0">
                                        <button @click="plusAccidentsNum" type="button" class="calculator__control calculator__control_plus">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="calculator__row pb-3 mb-0">
                            <!--<button class="btn btn_a" :disabled="buttonDisabled">Рассчитать стоимость</button>-->
                        </div>
                        <div class="calculator__result" v-if="showResult">
                            <h5>${ i18n.a10 }</h5>
                            
                            <div class="calculator__insurance-price">{{ displayBounty }} тг</div>
                            <small class="gray">${ i18n.a11 }</small>
                                
                            <form action="" method="post" @submit.prevent="sendForm" v-if="showForm" ref="orderForm">
                                <input type="hidden" name="csrf" :value="csrf" />
                                <div class="mt-3">
                                    <label for="phone" class="d-block">Телефон</label>
                                    <input v-model="phone"
                                            v-mask="'+# (###) ###-##-##'"
                                            :name="formName + '[phone]'"
                                            placeholder="+7 (___) ___-__-__"
                                            type="tel"
                                            id="phone"
                                            class="user-input">
                                </div>

                                <button type="submit" class="btn btn_a mt-4 mb-0 mb-md-3" :disabled="!phoneOk" ref="orderBtn">${ i18n.a12 }</button>
                                <div class="mt-2 mt-md-0 gray text-center text-md-left small">${ i18n.a13 }</div>
                            </form>
                            <div class="alert-success alert fade in mt-4" v-if="!showForm">
                                {{ formMessage }}
                            </div>
                        </div>
                        
                    </div>`,
        props: ['csrf', 'form-name'],
        data: function () {
            return {
                mzp: 42500,
                kprJson: kprJson,
                kprObj: null,

                // Количество работников с месячной зарплатой более 10 МЗП
                payedMore: 0,
                // ГФОТ работников с месячной зарплатой более 10 МЗП
                payedMoreFund: 0,

                // Количество работников с месячной зарплатой менее или равной 10 МЗП
                payedLess: 0,
                // ГФОТ работников с месячной зарплатой менее или равной 10 МЗП
                payedLessFund: 0,

                accidents: false,
                accidentsNum: 0,

                // Понижающий коэффициент (%)
                reductionFactor: 0,

                showForm: true,
                formMessage: '',
                phone: ''
            }
        },
        computed: {
            kpr() { return this.kprObj !== null ? this.kprObj.kpr : null },
            phoneOk() {
                return this.phone.length == 18
            },
            kprReady() {
                return parseInt(this.kpr)
            },
            // Тарифф
            tariff() {
                return kprToTariff[this.kprReady] / 100
            },
            correctionFactorX() {
                let x = 0

                let an = this.accidentsNum

                if(an >= 2 && an <= 9) {
                    x = 0
                } else if(an >= 10 && an <= 19) {
                    x = 1
                } else if(an >= 20 && an <= 49) {
                    x = 2
                } else if(an >= 50 && an <= 99) {
                    x = 3
                } else if(an >= 100 && an <= 199) {
                    x = 4
                } else if(an >= 200 && an <= 299) {
                    x = 5
                } else if(an >= 300) {
                    x = 6
                }

                return x
            },
            correctionFactorY() {
                // total eployees
                let te = parseInt(this.payedLess) + parseInt(this.payedMore)

                if(te <= 100) { // до 100
                    return 0
                }

                if(te <= 500) { // от 101 до 500
                    return 1
                }

                if(te <= 1000) { // от 501 до 1000
                    return 2
                }

                if(te <= 10000) { // от 1001 до 10000
                    return 3
                }

                if(te <= 20000) { // от 10001 до 20000
                    return 4
                }

                return 5
            },
            // Поправочный коэффициент
            correctionFactor() {

                let factor = 1

                if(this.accidents === false) {
                    factor = 1
                } else if (parseInt(this.payedLess) + parseInt(this.payedMore) > 0) {

                    let x = this.correctionFactorX
                    let y = this.correctionFactorY

                    factor = correctionFactorMatrix[x][y]
                }

                return factor
            },
            // Страховая сумма
            insuranceAmount() {
                return this.payedMore * 10 * this.mzp * 12 + parseInt(this.payedLessFund)
            },
            // Премия
            bounty() {
                return this.insuranceAmount * this.tariff * this.correctionFactor
            },
            // Коэффициент пропорционального  увеличения
            proportionalGain() {
                let gain = 1

                if(this.bounty <= this.mzp) {
                    gain = this.mzp / this.bounty
                } else {
                    gain = 1
                }

                return gain
            },
            // Ваша страховая сумма составит
            finalInsuranceAmount() {
                return this.insuranceAmount * this.proportionalGain
            },
            // Сумма страховой премии
            insuranceBounty() {
                return Math.round(this.bounty * this.proportionalGain * (1 - this.reductionFactor))
            },
            buttonDisabled() {
                let disabled = false

                if(isNaN(this.insuranceBounty)) {
                    disabled = true
                } else if(this.insuranceBounty <= 0) {
                    disabled = true
                }

                return disabled
            },
            displayBounty() {
                return this.insuranceBounty.toLocaleString('ru', {minimumFractionDigits: 0, maximumFractionDigits: 2})
            },
            showResult() {
                if (isNaN(this.insuranceBounty)) {
                    return false
                }

                return true
            }
        },
        methods: {
            nameWithCode ({ name, code }) {
                return `${code} — ${name}`
            },
            trans(msg) {
                if (i18n[msg]) {
                    return i18n[msg];
                } else {
                    return msg;
                }

                console.log(msg, i18n);
            },
            plusPayedMore() {
                this.payedMore += 1
                return this.payedMore
            },
            minusPayedMore() {
                this.payedMore = this.payedMore > 0 ? this.payedMore - 1 : 0
                return this.payedMore
            },
            plusPayedLess() {
                this.payedLess += 1
                return this.payedLess
            },
            minusPayedLess() {
                this.payedLess = this.payedLess > 0 ? this.payedLess - 1 : 0
                return this.payedLess
            },
            plusAccidentsNum() {
                const te = parseInt(this.payedMore) + parseInt(this.payedLess)

                if(te > this.accidentsNum) {
                    this.accidentsNum += 1
                }

                return this.accidentsNum
            },
            minusAccidentsNum() {
                this.accidentsNum = this.accidentsNum > 0 ? this.accidentsNum - 1 : 0
                return this.accidentsNum
            },
            sendForm: function () {
                const THIS = this

                let comment = ''
                comment += "Вид деятельности по ОКЭД:\n"
                comment += this.kprObj.name  + "\n\n"

                comment += "Сотрудники с зарплатой более 425 000 тг/мес. – " + this.payedMore + "\n"
                comment += "Годовой фонд оплаты труда – " + this.payedMoreFund + "\n"

                comment += "Сотрудники с зарплатой 425 000 тг/мес. и меньше – " + this.payedLess + "\n"
                comment += "Годовой фонд оплаты труда – " + this.payedLessFund + "\n\n"

                if(this.accidents !== false) {
                    comment += "Кол-во пострадавших в среднем за год – " + this.accidentsNum + "\n\n"
                }

                comment += "Cтоимость страховки: " + this.displayBounty + "тг\n\n"

                const form =  this.$refs.orderForm

                const btn = this.$refs.orderBtn
                btn.disabled = true
                btn.innerHTML = 'Отправляем заявку...'

                let formData = new FormData(form)
                formData.append('csrf', this.csrf)
                formData.append(THIS.formName + '[comment]', comment)

                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
                axios.post(document.URL, formData)
                    .then(function (response) {
                        console.log(response)

                        if (response.data.location != undefined) {
                            document.location.href = response.data.location
                        }

                        THIS.formMessage = response.data.message
                        THIS.showForm = false

                        btn.disabled = false
                        btn.innerHTML = 'Оставить заявку'
                    })
                    .catch(function (error) {
                        console.log(error)

                        THIS.formMessage = 'Произошла ошибка. Попробуйте еще раз.'
                        THIS.showForm = false

                        btn.disabled = false
                        btn.innerHTML = 'Оставить заявку'
                    });
            }
        },
        mounted() {
            // console.log(this.i18n)
        },
    });

    const calculatorOn = document.getElementById('calculator');
    if (calculatorOn) {

        const calculator = new Vue({
            el: '#calculator',
            data: {}
        });
    }
}());