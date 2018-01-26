<template>
    <section>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-file"></i>Report for 2/2 Hour</h3>
                <hr>
                <div class="form-inline">
                    <select v-model="selectedFilter" class="form-control">
                        <option value="" selected disabled>Filter</option>
                        <option value="0">Todos</option>
                        <option value="1">Por Usuario</option>
                    </select>
                    <component v-if="selectedFilter == 0" :is="'vue-datepicker'" v-model="params.pfecha_fin" @change="change()" type="datetime" format="yyyy-MM-dd HH:mm:ss" lang="es" placeholder="Fecha Fin"/>

                    <component v-else :is="'multiselect'" v-model="selectedUser" selectedLabel="Seleccionado"
                               deselectLabel="Remover" selectLabel="Seleccionar"
                               tag-placeholder="- seleccionar -" placeholder="- seleccionar -" label="value"
                               track-by="id"
                               :options="[{id:1,value:'alex quispe'},{id:2,value:'deysi quispe'},{id:3,value:'diana quispe'}]" class="w-25" />

                    <!--<v-select v-else v-model="selectedUser" :options="[{label:'alex',value:'Alex Quispe'}]" class="form-control"/>-->

                    <!--<input v-else type="text" class="form-control w-25" placeholder="Buscar por nombre o apellido"/>-->
                    <select class="form-control" v-model="params.puser_id" @change="change()">
                        <option value="0">todos</option>
                        <option value="48">48</option>
                        <option value="33">33</option>
                        <option value="35">35</option>
                        <option value="37">37</option>
                        <option value="40">40</option>
                    </select>
                    <select name="" id="" class="form-control"></select>
                    <button class="btn btn-success"><i class="fa fa-fw fa-file-excel-o"></i> Excel</button>
                    <button class="btn btn-success"><i class="fa fa-fw fa-file-excel-o"></i> Csv</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 9px'>
                        <tr v-if="params.puser_id != 0">
                            <th>Tiempo Rango Hora</th>
                            <th style='background-color: yellow'>Diff Inicial</th>
                            <th>Acd</th>
                            <th>Break</th>
                            <th>Sshh</th>
                            <th>Refrigerio</th>
                            <th>Feedback</th>
                            <th>Capacitacion</th>
                            <th>Backoffice</th>
                            <th>Inbound</th>
                            <th>Outbound</th>
                            <th>Login</th>
                            <th>Ring Inbound</th>
                            <th>Ring Outbound</th>
                            <th>Hold Inbound</th>
                            <th>Hold Outbound</th>
                            <th>Ring Inbound Interno</th>
                            <th>Inbound Interno</th>
                            <th>Outbound Interno</th>
                            <th>Ring Outbound Interno</th>
                            <th>Hold Inbound Interno</th>
                            <th>Hold Outbound Interno</th>
                            <th>Ring Inbound Transfer</th>
                            <th>Inbound Transfer</th>
                            <th>Hold Inbound Transfer</th>
                            <th>Ring Outbound Transfer</th>
                            <th>Hold Outbound Transfer</th>
                            <th>Desconectado</th>
                            <th style='background-color: red'>Diff Final</th>
                            <th>Total</th>
                        </tr>
                        <tr v-if="params.puser_id == 0">
                            <th colspan="29">Full Users</th>
                        </tr>
                        </thead>
                        <tbody v-if="params.puser_id != 0">
                        <tr v-if="!loading" v-for="(value,key) in dataReport">
                            <td class="small">{{key}}</td>
                            <td>{{value.diff_inicial}}</td>
                            <td>{{value.acd}}</td>
                            <td>{{value.break}}</td>
                            <td>{{value.sshh}}</td>
                            <td>{{value.refrigerio}}</td>
                            <td>{{value.feedback}}</td>
                            <td>{{value.capacitacion}}</td>
                            <td>{{value.backoffice}}</td>
                            <td>{{value.inbound}}</td>
                            <td>{{value.outbound}}</td>
                            <td>{{value.login}}</td>
                            <td>{{value.ring_inbound}}</td>
                            <td>{{value.ring_outbound}}</td>
                            <td>{{value.hold_inbound}}</td>
                            <td>{{value.hold_outbound}}</td>
                            <td>{{value.ring_inbound_interno}}</td>
                            <td>{{value.inbound_interno}}</td>
                            <td>{{value.outbound_interno}}</td>
                            <td>{{value.ring_outbound_interno}}</td>
                            <td>{{value.hold_inbound_interno}}</td>
                            <td>{{value.hold_outbound_interno}}</td>
                            <td>{{value.ring_inbound_transfer}}</td>
                            <td>{{value.inbound_transfer}}</td>
                            <td>{{value.hold_inbound_transfer}}</td>
                            <td>{{value.ring_outbound_transfer}}</td>
                            <td>{{value.hold_outbound_transfer}}</td>
                            <td>{{value.desconectado}}</td>
                            <td>{{value.diff_final}}</td>
                            <td>{{value.total}}</td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="30">
                                <div>
                                    <p></p>
                                    <i class="fa fa-circle-o-notch fa-spin"></i>
                                    <p></p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-if="params.puser_id == 0">
                        <tr v-if="!loading" v-for="(v,k) in dataReport">
                            <td>
                                <table border='1'
                                       style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 12px'>
                                    <tr v-if="!loading" v-for="(vv,kk) in v">
                                        <!--<td>-->
                                        <!--<table border='1' style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 12px'>-->
                                        <!--<tr v-if="!loading" v-for="(vvv,kkk) in vv">-->
                                        <td>{{kk}}</td>
                                        <td>
                                            <table border='1'
                                                   style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 12px'>
                                                <tr v-if="!loading" v-for="(vvv,kkk) in vv">
                                                    <td>{{kkk}}</td>
                                                    <td>{{vvv.diff_inicial}}</td>
                                                    <td>{{vvv.acd}}</td>
                                                    <td>{{vvv.break}}</td>
                                                    <td>{{vvv.sshh}}</td>
                                                    <td>{{vvv.refrigerio}}</td>
                                                    <td>{{vvv.feedback}}</td>
                                                    <td>{{vvv.capacitacion}}</td>
                                                    <td>{{vvv.backoffice}}</td>
                                                    <td>{{vvv.inbound}}</td>
                                                    <td>{{vvv.outbound}}</td>
                                                    <td>{{vvv.login}}</td>
                                                    <td>{{vvv.ring_inbound}}</td>
                                                    <td>{{vvv.ring_outbound}}</td>
                                                    <td>{{vvv.hold_inbound}}</td>
                                                    <td>{{vvv.hold_outbound}}</td>
                                                    <td>{{vvv.ring_inbound_interno}}</td>
                                                    <td>{{vvv.inbound_interno}}</td>
                                                    <td>{{vvv.outbound_interno}}</td>
                                                    <td>{{vvv.ring_outbound_interno}}</td>
                                                    <td>{{vvv.hold_inbound_interno}}</td>
                                                    <td>{{vvv.hold_outbound_interno}}</td>
                                                    <td>{{vvv.ring_inbound_transfer}}</td>
                                                    <td>{{vvv.inbound_transfer}}</td>
                                                    <td>{{vvv.hold_inbound_transfer}}</td>
                                                    <td>{{vvv.ring_outbound_transfer}}</td>
                                                    <td>{{vvv.hold_outbound_transfer}}</td>
                                                    <td>{{vvv.desconectado}}</td>
                                                    <td>{{vvv.diff_final}}</td>
                                                    <td>{{vvv.total}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <!--</tr>-->
                                        <!--</table>-->
                                        <!--</td>-->
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="30">
                                <div>
                                    <p></p>
                                    <center>LOADING TABLE...</center>
                                    <p></p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </section>
</template>

<script>
    import Vue from 'vue';
    import Service from '../services/ReportService';
    import DatePicker from 'vue2-datepicker';

    import vSelect from 'vue-select';
    Vue.component('v-select', vSelect);

    import Multiselect from 'vue-multiselect';
    Vue.component('multiselect', Multiselect);

    Vue.component('vue-datepicker', DatePicker);

    export default {
        data: () => ({
            loading: false,
            params: {
                tipo: 1800,
                pfecha_ini: "",
                pfecha_fin: "",
                puser_id: "48",
                prol: "user",
            },
            dataReport: [],
            dataObj: {},
            model_date_1: "",
            model_date_2: "",
            selectedFilter : "",
            selectedUser : "",

        }),
        beforeCreate() {

        },
        created() {
            // this.list();
        },
        methods: {
            list() {
                Service.dispatch("list", {self: this});
            },
            change() {
                this.loading = true;
                if (this.params.pfecha_ini != "" && this.params.pfecha_fin != "") {
                    this.list();
                }
            }
        }
    }
</script>

<style scoped>

</style>