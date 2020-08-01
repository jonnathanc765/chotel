<template>
    <b-list-group-item href="#" class="flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"># {{ reservation.id }} | Type: {{ reservation.room.type.description }}</h5>
            <small>Beds: {{ reservation.room.type.beds }}</small>
        </div>

        <b-row>
            <b-col cols="6">
                <p class="mb-1">
                    Check in: {{ reservation.check_in }}
                </p>
                <p class="mb-1">
                    Check out: {{ reservation.check_out }}
                </p>
            </b-col>
            <b-col cols="6" class="d-flex justify-content-end align-items-center">
                <b-button :variant="isEditing ? 'success' : 'info'" @click="toggleEditingStatus">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <b-button variant="danger" @click="destroy">
                    <b-spinner v-if="isSubmit"></b-spinner>
                    <b-icon-trash v-else></b-icon-trash>
                </b-button>
            </b-col>
        </b-row>
        <b-row v-if="isEditing">
            <b-col cols="12">
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
                    <b-col cols="12" class="d-flex justify-content-center">
                        <b-button variant="danger" @click="update()">
                            <b-spinner v-if="isSubmit"></b-spinner>
                            <span v-else>Update reservation</span>
                        </b-button>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
    </b-list-group-item>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
export default {
    props: ['reservation', 'index'],
    
    data() {
        return {
            isSubmit: false,
            isEditing: false,
            form: {
                type: '',
                from: '',
                to: '',
            },
            options: [
                { value: '', text: 'Select a room type' },
                { value: 1, text: 'Familiar' },
                { value: 2, text: 'Double' },
                { value: 3, text: 'Singular ' }
            ]
        }
    },
    methods: {
        toggleEditingStatus() {
            this.isEditing = !this.isEditing;
        },
        async destroy() {

            if (this.isSubmit) {
                return;
            }

            this.isSubmit = true;

            await this.$store.commit('destroyReservation', this.reservation.id)

        },
        update() {

            if (this.isSubmit) {
                return;
            }

            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }

            let self = this;

            this.isSubmit = true;

            this.$http.post(`http://127.0.0.1:8000/api/room/reservation/${this.reservation.id}`, {
                _method: 'PUT',
                type_id: this.form.type,
                checkin: this.form.from,
                checkout: this.form.to
            })
            .then(response => {

                console.log(response.data);
                self.isSubmit = false;
                self.isEditing = false;
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