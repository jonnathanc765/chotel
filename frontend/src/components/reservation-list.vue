<template>

        <b-row>
            <b-col cols="12">
                <h2>List of reservation</h2>
                <b-list-group v-if="reservations.length > 0">
                    <reservation v-for="reservation of reservations" :key="reservation.id" :reservation="reservation"></reservation>
                </b-list-group>
                <h3 class="text-center" v-else>The reservations list its empty</h3>
            </b-col>
        </b-row>


</template>

<script>

import { mapState } from 'vuex'
import reservation from "./reservation";

export default {
    data() {
        return {

        }
    },
    components: {
        reservation
    },
    computed: mapState({
        reservations: state => state.reservations
    }),
    mounted() {
 
        this.$store.commit('getReservations');


    },
    methods: {
        
        
    }

}
</script>
