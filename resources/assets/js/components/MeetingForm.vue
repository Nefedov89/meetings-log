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
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="from_h" class="control-label">From, hours</label>
                                    <input v-model="from_h" type="number" name="from_h" min="0" max="23" class="form-control">
                                    <span class="text-danger" v-if="errors.from_h">{{ errors.from_h[0] }}</span>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="from_m" class="control-label">From, minutes</label>
                                    <input v-model="from_m" type="number" name="from_m" min="0" max="59" class="form-control">
                                    <span class="text-danger" v-if="errors.from_m">{{ errors.from_m[0] }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="to_h" class="control-label">To, hours</label>
                                    <input v-model="to_h" type="number" name="to_h" min="1" max="24" class="form-control">
                                    <span class="text-danger" v-if="errors.to_h">{{ errors.to_h[0] }}</span>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="to_m" class="control-label">To, minutes</label>
                                    <input v-model="to_m" type="number" name="to_m" min="0" max="59" class="form-control">
                                    <span class="text-danger" v-if="errors.to_m">{{ errors.to_m[0] }}</span>
                                </div>
                            </div>

                            <button v-on:click="createMeeting" type="button" class="btn btn-primary">Add meeting</button>
                        </form>
                    </div>
                </div>
                <router-link to="/empty-date-rate">Go to Empty date rate</router-link>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h4>Meetings list</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="meeting in meetings">
                            <td>{{ meeting.name }}</td>
                            <td>{{ meeting.from }}</td>
                            <td>{{ meeting.to }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                name: '',
                from_h: '',
                from_m: '',
                to_h: '',
                to_m: '',
                errors: {},
                success: false,
                meetings: []
            }
        },
        methods: {
            createMeeting: function() {
                let scope = this;

                scope.errors = {};
                scope.sucess = false;

                $.post(
                    '/add-meeting',
                    {
                        'name': this.name,
                        'from_h': this.from_h,
                        'from_m': this.from_m,
                        'to_h': this.to_h,
                        'to_m': this.to_m,
                    },
                    function (data){
                        if (data.errors !== undefined) {
                            scope.errors = data.errors;
                        } else {
                            scope.success = true;
                            scope.clearInputs();
                            scope.getCreatedMeetings();
                        }
                    }
                );
            },
            clearInputs: function() {
                this.name = this.from_h = this.from_m = this.to_h = this.to_m = '';
            },
            getCreatedMeetings: function() {
                let scope = this;

                $.get('/meetings', function (data){
                    scope.meetings = data.meetings;
                });
            }
        },
        mounted: function() {
            let scope = this;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Get list of all created meetings.
            scope.getCreatedMeetings();
        }
    }
</script>