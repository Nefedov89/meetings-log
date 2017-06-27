<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>Page #2</h2>
                <div class="panel panel-info">
                    <div class="panel-heading">Empty day rate</div>

                    <div class="panel-body">
                        {{ rate }}
                    </div>
                </div>

                <router-link to="/">Go to Meeting form</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                rate: ''
            }
        },
        methods: {
            emptyDateRate: function() {
                var scope = this;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.get('/get-empty-rate', function (data){
                    scope.rate = data.rate;
                });
            }
        },
        mounted: function() {
            let scope = this;

            scope.emptyDateRate();
        }
    }
</script>
