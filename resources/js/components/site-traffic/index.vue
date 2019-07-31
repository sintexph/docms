<template>
    <div>
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 v-text="total_traffic"></h3>

                        <p>Total Visitors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 v-text="total_weekdays"></h3>

                        <p>This Week Visitor</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 v-text="total_days_month"></h3>

                        <p>This Month Visitor</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-body">
                <button type="button" class="btn btn-xs btn-default" @click.prevent="reset"><i class="fa fa-trash"
                        aria-hidden="true"></i>
                    Clear Traffic</button>
                <button type="button" @click.prevent="$refs.modal.show()" class="btn btn-xs btn-default"><i
                        class="fa fa-calendar" aria-hidden="true"></i> View Specific
                    Date</button>

                <modal name="modal-change-date" ref="modal">
                    <template slot="header">Set Specific Date</template>
                    <template slot="body">
                        <date-mask :required="true" v-model="custom_date"></date-mask>
                    </template>
                    <template slot="footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click.prevent="reload">Reload</button>
                    </template>
                </modal>


            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">This Week's Traffic</h3>
            </div>
            <div class="box-body">
                <weekdays-traffic :custom_date="specific_date"></weekdays-traffic>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">This Month's Traffic</h3>
            </div>
            <div class="box-body">
                <days-month-traffic :custom_date="specific_date"></days-month-traffic>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                specific_date: '',
                submitted: false,
                custom_date: '',
                total_traffic: 0,
                total_weekdays: 0,
                total_days_month: 0,
            }
        },
        methods: {
            total_traffics() {
                var vm = this;
                axios.post('/traffic/total', {
                    sd: vm.specific_date,
                }).then(response => {
                    var data = response.data;
                    vm.total_traffic = data.total;
                    vm.total_weekdays = data.total_weekdays;
                    vm.total_days_month = data.total_days_month;
                });
            },
            reload() {

                this.specific_date = this.custom_date;
                this.total_traffics();

                this.$refs.modal.dismiss()

            },
            reset() {
                var vm = this;
                if (vm.submitted === false) {
                    if (confirm("Are you sure you want to clear the site traffic?")) {
                        par.show_wait("Please wait while the system is posting your comment...");
                        axios.post('/traffic/reset').then(response => {
                            vm.hide_wait();
                            location.reload();
                        });
                    }
                }

            }
        },
        mounted() {
            this.total_traffics();
        }
    }

</script>
