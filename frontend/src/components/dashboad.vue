<template>
    <b-container>
        <b-row>
            <b-col cols="12">
                <h2>CHotel</h2>

                <h3>Room type</h3>
                <searchForm></searchForm>

            </b-col>
        </b-row>

        <reservationList></reservationList>

    </b-container>
</template>

<script>
import reservationList from './reservation-list';
import searchForm from './search-form';

export default {
    components: {
        reservationList,
        searchForm
    },

}
</script>