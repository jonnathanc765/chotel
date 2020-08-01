<template>
  <div>
        <b-form-group>
            <b-form-select v-model.trim="form.type" :options="options" :state="validateState('type')" @change="resetAvalaible()">
            </b-form-select>
            <b-form-invalid-feedback :state="validateState('type')">The type field is required.</b-form-invalid-feedback>
        </b-form-group>

        <b-row>
            <b-col cols="6">
                <h3>From:</h3>
                <b-form-group>
                    <b-datepicker v-model="form.from" :state="validateState('from')" @change="resetAvalaible()">
                    </b-datepicker>
                    <b-form-invalid-feedback :state="validateState('from')">
                        The start day is require
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-col>
                <b-col cols="6">
                <h3>To:</h3>
                <b-datepicker v-model="form.to" :state="validateState('to')" @change="resetAvalaible()">
                </b-datepicker>
                <b-form-invalid-feedback :state="validateState('to')">
                    The start day is require
                </b-form-invalid-feedback>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="12" class="my-3 d-flex justify-content-center">
                <b-button variant="warning" @click="checkAvalaibility()">
                    <b-spinner small v-if="isSubmit"></b-spinner>
                    Check avalaibility
                </b-button>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" class="d-flex jsutify-content-center flex-column align-items-center">
                <h3>
                    Rooms quantity avalaible: {{ avalaible }}
                </h3>
                <b-button @click="makeReservation()" :disabled="avalaible <= 0" :variant="avalaible <= 0 ? 'secondary' : 'primary'">
                    <b-spinner small v-if="isSubmit"></b-spinner>
                    Make a reservation for this type
                    <b-icon-check2-circle v-if="avalaible > 0"></b-icon-check2-circle>
                </b-button>
            </b-col>
        </b-row>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState } from 'vuex'
export default {
    data() {
        return {
            form: {
                type: '',
                from: '',
                to: '',
            },
            isSubmit: false,
            avalaible: 0,
            options: [
                { value: '', text: 'Select a room type' },
                { value: 1, text: 'Familiar' },
                { value: 2, text: 'Double' },
                { value: 3, text: 'Singular ' }
            ]
        }
    },
    computed: mapState({
        reservations: state => state.reservations
    }),
    methods: {
        checkAvalaibility() {

            if (this.isSubmit) {
                return;
            }

            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }

            this.resetAvalaible();

            let self = this;

            this.isSubmit = true;

            this.$http.get(`http://127.0.0.1:8000/api/room/availability/${this.form.type}/${this.form.from}/${this.form.to}`)
            .then(response => {
                
                this.avalaible = response.data.avalaible;
                self.isSubmit = false;

            })
            .catch(error => {
                console.log(error);
                self.isSubmit = false;
                this.$bvToast.toast('A error was ocurred!, try again', {
                    title: 'Important Info',
                    variant: 'danger',
                    solid: true
                });
            });


        },
        makeReservation() {

            if (this.isSubmit) {
                return;
            }

            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }

            let self = this;

            this.isSubmit = true;

            this.$http.post(`http://127.0.0.1:8000/api/room/reservation/`, {
                type_id: this.form.type,
                checkin: this.form.from,
                checkout: this.form.to
            })
            .then(response => {

                console.log(response.data);
                self.isSubmit = false;
                // Push notification 
                this.$bvToast.toast(response.data.msg, {
                    title: 'Important info',
                    variant: 'success',
                    solid: true
                });
                self.$store.commit('getReservations');
                self.resetForm();
                self.avalaible = 0;

            })
            .catch(error => {
                // Push notification 
                this.$bvToast.toast('A error was ocurred! Try again', {
                    title: 'Important Info',
                    variant: 'danger',
                    solid: true
                });

                console.log('Toast: ',error);

                self.isSubmit = false;

            });
        },
        validateState(name) {
            const { $dirty, $error } = this.$v.form[name];
            return $dirty ? !$error : null;
        },
        resetForm() {
            this.form.type = '';
            this.form.from = '';
            this.form.to = '';
        },
        resetAvalaible() {
            this.avalaible = 0;
        }
    },
    validations: {
        form: {
            type: {
                required,
            },
            from: {
                required 
            },
            to: {
                required
            }
        }
    }
}
</script>

<style>

</style>