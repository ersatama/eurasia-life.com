import Vue from '../node_modules/vue/dist/vue';
import VueMask from '../node_modules/v-mask/dist/v-mask'

import axios from '../node_modules/axios/dist/axios.min.js'

Vue.use(VueMask);

Vue.component('calc-annuitet', {
    template: `<div class="calculator">
                <h2>Расчет пенсии</h2>
              
                <div class="calculator__row text-nowrap">
                    <label for="birthday">Дата рождения</label>
                    <input v-model="birthday" v-mask="'##.##.####'" type="text" id="birthday" class="" placeholder="__.__.____">
                    <div class="text-danger text-wrap small mt-2" v-if="dateError">{{dateError}}</div>
                </div>
                <div class="calculator__row text-nowrap">
                    <label for="sex">Пол</label>
                    <div class="d-flex flex-nowrap">
                        <button @click="sex=1" class="calculator__sex-btn" :class="{active: sex === 1}">
                            <svg width="28px" height="28px" viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Положительный" transform="translate(-86.000000, -338.000000)" fill="#778E93" fill-rule="nonzero">
                                        <g id="mars" transform="translate(86.000000, 338.000000)">
                                            <path d="M27.984904,1.01627604 C27.9720866,0.915730813 27.9498698,0.818888365 27.9131266,0.728027344 C27.9119873,0.725748698 27.9119873,0.722615542 27.9114176,0.719767271 C27.9114176,0.719197573 27.910848,0.718627948 27.9102783,0.71805825 C27.8698324,0.622355125 27.8145752,0.535481771 27.7521972,0.454874656 C27.7368164,0.435790979 27.7217204,0.416992188 27.7054851,0.399047833 C27.6388346,0.323852521 27.5664877,0.254353823 27.4824626,0.197672563 C27.4801839,0.195963542 27.4773356,0.195393917 27.4750569,0.193684896 C27.3938802,0.140136719 27.3041585,0.0996907917 27.2101644,0.06778975 C27.1868083,0.0595296042 27.1640218,0.0524088542 27.140096,0.0461425417 C27.0415446,0.0193684896 26.9401449,0 26.8333333,0 L18.6666667,0 C18.0226644,0 17.5,0.522664406 17.5,1.16666667 C17.5,1.81066893 18.0226644,2.33333333 18.6666667,2.33333333 L24.0163575,2.33333333 L17.0531006,9.29659019 C15.1962891,7.81148273 12.9102377,7 10.5,7 C4.71053056,7 0,11.7105306 0,17.5 C0,23.2894694 4.71053056,28 10.5,28 C16.2894694,28 21,23.2894694 21,17.5 C21,15.0909017 20.1890869,12.80542 18.7028402,10.9468994 L25.6666667,3.98307292 L25.6666667,9.33333333 C25.6666667,9.97733559 26.1893311,10.5 26.8333333,10.5 C27.4773356,10.5 28,9.97733559 28,9.33333333 L28,1.16666667 C28,1.14217119 27.9943034,1.1188151 27.9928793,1.09488932 C27.9911702,1.06811527 27.988322,1.04248047 27.984904,1.01627604 Z M10.5,25.6666667 C5.99654132,25.6666667 2.33333333,22.0034587 2.33333333,17.5 C2.33333333,12.9965413 5.99654132,9.33333333 10.5,9.33333333 C12.6803792,9.33333333 14.7331543,10.1821289 16.276652,11.7202148 C17.8178711,13.2668457 18.6666667,15.3196207 18.6666667,17.5 C18.6666667,22.0034587 15.0034587,25.6666667 10.5,25.6666667 Z" id="Shape"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg> Муж.
                        </button>
                        <button @click="sex=2" class="calculator__sex-btn" :class="{active: sex === 2}">
                            <svg width="21px" height="32px" viewBox="0 0 21 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Положительный" transform="translate(-212.000000, -337.000000)" fill="#778E93" fill-rule="nonzero">
                                        <g id="femenine" transform="translate(212.000000, 337.000000)">
                                            <path d="M17.9281015,18.1358615 C22.0239662,13.9925449 22.0239662,7.25085083 17.9281015,3.10753428 C13.8322368,-1.03584476 7.167825,-1.03584476 3.07189853,3.10753428 C-1.02396618,7.25085083 -1.02396618,13.9925449 3.07189853,18.1358615 C4.80563382,19.8896757 6.99957794,20.9008642 9.26405735,21.1699894 L9.26405735,24.7276804 L6.79235735,24.7276804 C6.10979559,24.7276804 5.55650735,25.2874309 5.55650735,25.9778691 C5.55650735,26.6683073 6.10979559,27.2280578 6.79235735,27.2280578 L9.26405735,27.2280578 L9.26405735,30.7498113 C9.26411912,31.4402495 9.81740735,32 10.5000309,32 C11.1825926,32 11.7358809,31.4402495 11.7358809,30.7498113 L11.7358809,27.2280578 L14.2076426,27.2280578 C14.8902044,27.2280578 15.4434926,26.6683073 15.4434926,25.9778691 C15.4434926,25.2874309 14.8902044,24.7276804 14.2076426,24.7276804 L11.7358809,24.7276804 L11.7358809,21.1700519 C14.0004221,20.9008642 16.1943662,19.8896757 17.9281015,18.1358615 Z M4.81971618,16.3677972 C1.68756618,13.1993566 1.68756618,8.04397661 4.81971618,4.87553601 C7.95180441,1.70722042 13.0480721,1.70703292 16.1803456,4.87553601 C19.3124956,8.04397661 19.3124956,13.1993566 16.1803456,16.3677972 C13.0481956,19.5361753 7.95186618,19.5361753 4.81971618,16.3677972 Z" id="Shape"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg> Жен.
                        </button>
                    </div>
                </div>
                <div class="calculator__row text-nowrap pb-5">
                    <label for="deposit">Сумма накоплений</label>
                    <input v-model.number="deposit" type="number" id="deposit" class="calculator__fund mb-2" placeholder="0" />
                    <span>тг</span>
                    <br/>
                    <div class="text-danger text-wrap small mt-1" v-if="depositError"><span v-html="depositError"></span></div>
                    <small class="gray">Если не знаете, уточните в ЕНПФ</small>
                </div>
                <!--div class="calculator__row pb-5">
                    <label for="guarantee">Гарантированный период</label>
                    <p>Срок, в течение которого вам или вашим наследникам гарантируются ежемесячные выплаты</p>
                    <div class="d-inline-block position-relative mb-2">
                        <button @click="minusGuarantee" type="button" class="calculator__control calculator__control_minus">-</button>
                        <input v-model="guarantee" type="number" id="guarantee" class="calculator__number" placeholder="0">
                        <button @click="++guarantee" type="button" class="calculator__control calculator__control_plus">+</button>
                    </div>
                    <br/>
                    <small class="gray">Выберите, чтобы гарантировать выплаты вашим наследникам</small>
                </div-->
                
                <div class="calculator__result" v-if="payEveryMonth>0">
                    <h5>Сумма ежемесячной выплаты</h5>
                    
                    <div class="calculator__insurance-price">{{ payEveryMonth }} тг</div>
                    
                    <div class="gray small py-3 mr-md-5 pr-md-5">Расчёт является предварительным. Чтобы узнать точный размер выплат, отправьте заявку.</div>
                        
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

                        <button type="submit" class="btn btn_a mt-4 mb-0 mb-md-3" :disabled="!phoneOk" ref="orderBtn">Оставить заявку</button>
                        <div class="mt-2 mt-md-0 gray text-center text-md-left small">Перезвоним в течение 10 минут</div>
                    </form>
                    <div class="alert-success alert fade in mt-4" v-if="!showForm">
                        {{ formMessage }}
                    </div>
                </div>
              </div>`,
    props: {
        csrf       : String,
        'form-name': String,
        today      : String
    },
    data() {
        return {
            birthday : null,
            sex      : null,
            deposit  : null,
            guarantee: null,

            // минимальная пенсия
            minimumPension: 36108,

            showForm: true,
            formMessage: '',
            phone: ''
        };
    },
    computed: {
        phoneOk() {
            return this.phone.length == 18
        },

        yearsOld() {

            if (null !== this.birthday && this.birthday.length == 10) {

                let splitBirthday = this.birthday.split('.');
                let birthdayObj = new Date(splitBirthday[2] + '-' + splitBirthday[1] + '-' + splitBirthday[0]);
                let today = new Date(this.today);
                let age = today.getFullYear() - birthdayObj.getFullYear();
                let m = today.getMonth() - birthdayObj.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthdayObj.getDate())) {
                    age--;
                }
                return age;

            } else {
                return null
            }

            // console.log(birthdayObj.getTime());
        },

        dateError() {
            if(this.sex == 1 && this.yearsOld != null && this.yearsOld < 55) {
                return 'Возраст для заключения договора — 55 лет';
            } else if(this.sex == 2 && this.yearsOld != null && this.yearsOld < 51) {
                return 'Возраст для заключения договора — 51 год';
            }

            return false;
        },

        depositError() {
            if (this.sex == 1 && this.deposit != null && this.deposit < 6300000) {
                return 'Сумма ваших накоплений недостаточна, минимальная — 6&nbsp;300&nbsp;000&nbsp;тг';
            } else if (this.sex == 2 && this.deposit != null && this.deposit < 8800000) {
                return 'Сумма ваших накоплений недостаточна, минимальная — 8&nbsp;800&nbsp;000&nbsp;тг';
            }

            return false;
        },

        // Премия дисконтированная
        discountPremium() {

            // Аннуитетный фактор с расх
            // let factor = this.roundToTwo(this.annuitetFactor * (1 + 0.03) / (1 - 0));
            let factor = 26.150;


            console.log(factor * 12);

            if ((this.sex == 1 && this.yearsOld >= 55) || (this.sex == 2 && this.yearsOld >= 51)) {
                return factor * 12;
            } else {
                return "возраст не соответствует";
            }
        },

        annuitetFactor() {
            // Накопл. Дожитие с гарантией
            // значение, если "Гарантированный период" = 0
            // Надо вычислять при смене "Гарантированный период"
            // ХЗ что это, разибраюсь...
            let nakopl = 25.4800;

            // nakopl = 0;
            //
            // // от текущего возраста до 110 лет
            // for (let i = this.yearsOld; i <= 110; i++) {
            //
            //     // Накопл. дожитие
            //     let i21 = '';
            //
            //     let j21 = '';
            //     let e21 = '';
            //
            //     if(i < this.yearsOld + this.guarantee) {
            //         nakopl += 1 * j21;
            //     } else {
            //
            //     }
            // }


            return nakopl;
        },

        // Минимальная сумма накоплений для заключения договора
        minimumSavedForCоntract() {
            return this.minimumPension * this.discountPremium;
        },

        // Ежемесячная страховая выплата
        payEveryMonth() {

            if( this.deposit >= this.minimumSavedForCоntract) {
                return Math.round(this.deposit / this.discountPremium);
            } else {
                return 0;
            }
        },

        // Количество гарантированных выплат
        paymentNumber() {
            return this.guarantee * 12;
        }
    },
    methods : {
        minusGuarantee() {
            this.guarantee = this.guarantee > 0 ? this.guarantee - 1 : 0
            return this.guarantee
        },

        roundToTwo(num) {
            return +(Math.round(num + "e+2")  + "e-2");
        },
        sendForm: function () {
            const THIS = this;

            let comment = '';
            comment += "Дата рождения:" + this.birthday + "\n\n";

            comment += "Пол: " + this.sex + "\n\n";

            comment += "Сумма накоплений: " + this.deposit + "\n\n";

            comment += "Сумма ежемесячной выплаты: " + this.payEveryMonth + "тг\n\n"

            const form =  this.$refs.orderForm;

            const btn = this.$refs.orderBtn;
            btn.disabled = true;
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
});


const calculatorOn = document.getElementById('calculator');
if (calculatorOn) {

    const calculator = new Vue({
        el  : '#calculator',
        data: {}
    });
}