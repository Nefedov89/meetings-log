<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Page #1</h1>
                <div class="panel panel-info">
                    <div class="panel-heading">Add new room</div>
                    <div class="panel-body">
                    <div class="alert-success alert" v-if="success">Meeting was created</div>
                        <form>
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input v-model="name" type="text" name="name" class="form-control">
                                <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label for="from" class="control-label">From</label>
                                <input v-model="from" type="number" name="from" min="0" max="23" class="form-control">
                                <span class="text-danger" v-if="errors.from">{{ errors.from[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label for="to" class="control-label">To</label>
                                <input v-model="to" type="number" name="to" min="1" max="24" class="form-control">
                                <span class="text-danger" v-if="errors.to">{{ errors.to[0] }}</span>
                            </div>

                            <button v-on:click="createMeeting" type="button" class="btn btn-primary">Add meeting</button>
                        </form>
                    </div>
                </div>
                <router-link to="/empty-date-rate">Go to Empty date rate</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                name: '',
                from: '',
                to: '',
                errors: {},
                success: false
            }
        },
        methods: {
            createMeeting: function() {
                let scope = this;

                scope.errors = {};
                scope.sucess = false;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post('/add-meeting', {'name': this.name, 'from': this.from, 'to': this.to}, function (data){
                    scope.email = data.email;

                    if (data.errors !== undefined) {
                        scope.errors = data.errors;
                    } else {
                        scope.success = true;
                    }
                });
            }
        }
    }
</script>