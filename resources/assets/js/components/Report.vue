<template>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Configuración</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr class="bg-light">
                                <th>Limpiar Filtros Seleccionados</th>
                                <th class="text-right">
                                    <button @click.prevent="cleanCheckeds" class="btn btn-secondary">Limpiar</button>
                                </th>
                            </tr>
                            <tr>
                                <td><i v-show="checkedFilterTime" class="fa fa-check text-success fa-fw"></i>Habilitar Filtrar con Tiempo</td>
                                <td class="text-right">
                                    <div class="m-auto">
                                        <div class="form-check">
                                            <input v-model="checkedFilterTime" type="checkbox" class="form-check-input">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 m-auto">
                        <span class="card-title">Reporte por Hora</span>
                    </div>
                    <div class="col-6 m-auto text-right">
                        <!-- Button trigger modal -->
                        <button :disabled="disabledFilter" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-cogs"></i>
                            <span>Opciones</span>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="form-inline">
                    <date-picker v-model="selectedFecha"
                                 range
                                 :type="checkedFilterTime ? 'datetime' : 'date'"
                                 :format="checkedFilterTime ? 'YYYY-MM-DD HH:mm' : 'YYYY-MM-DD'"
                                 :time-picker-options="{start: '00:00',step: '00:30',end: '23:30'}"
                                 lang="es"
                                 placeholder="Fecha"
                                 :disabled="disabledFilter"
                                 class="w-50"
                                 confirm
                                 @input="change()"/>
                    <multiselect v-model="selectedUser"
                                 selectedLabel="Seleccionado"
                                 deselectLabel="Remover"
                                 selectLabel="Seleccionar"
                                 placeholder="Buscar"
                                 :options="dataUsers"
                                 label="value"
                                 track-by="id"
                                 style="width: 300px !important;"
                                 :disabled="disabledFilter"
                                 @input="change()"/>
                    <label>
                        <select v-model="params.rol" :disabled="disabledFilter" class="form-control"
                                @change="change()">
                            <option value="user" selected>User</option>
                            <option value="backoffice" selected>BackOffice</option>
                        </select>
                    </label>
                    <div class="btn-group dropdown btn-group-xs" role="group" aria-label="Reserve Options">
                        <button @click="exportFile('xlsx')" :disabled="params.user_id == '' || disabledFilter"
                                type="button"
                                class="btn btn-success" title="Exportar por defecto">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span>Excel</span>
                        </button>
                        <div class="btn-group open" role="group">
                            <button :disabled="disabledFilter" type="button"
                                    class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="true">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                                <li title="Exportar">
                                    <button @click="exportFile('xlsx')" :disabled="params.user_id == '' "
                                            class="dropdown-item text-muted"><i class="fa fa-file-excel-o fa-fw"></i>
                                        <small>Por Usuario</small>
                                    </button>
                                    <button @click="exportFile('xlsx',0)" class="dropdown-item text-muted"><i
                                            class="fa fa-file-excel-o fa-fw"></i>
                                        <small>Todos</small>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button :disabled="disabledFilter" class="btn btn-secondary" title="actualizar lista!"
                            @click="refresh()">
                        <i class="fa fa-fw fa-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table v-if="!loading && selectedUser == null && dataReport.length == 0" style="width: 100%">
                        <tbody>
                        <tr>
                            <td colspan="auto" class="text-dark text-center">
                                <div style="padding: 2em 2em 0 2em">
                                    <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                                    <p>No hay información disponible!</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table v-if="loading && selectedUser != null && dataReport.length == 0" style="width: 100%">
                        <tbody>
                        <tr>
                            <td colspan="auto" class="text-dark text-center">
                                <div style="padding: 2em 2em 0 2em">
                                    <i class="fa fa-circle-o-notch fa-spin fa-2x mb-2"></i>
                                    <p>Obteniendo Información!</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table v-if="!loading && selectedUser != null" class="table small table-condensed">
                        <thead v-if="selectedFilter == 0 && !loading">
                        <tr>
                            <th>Rango Hora</th>
                            <th>Diff Inicial</th>
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
                            <th>Nivel Ocupacion</th>
                            <th>Nivel Ocupacion Backoffice</th>
                        </tr>
                        </thead>
                        <thead v-if="selectedFilter == 1 && !loading">
                        <tr>
                            <th colspan="29">Full Users</th>
                        </tr>
                        </thead>
                        <tbody v-if="selectedFilter == 0 && !loading">
                        <tr v-for="(value,key) in dataReport">
                            <td class="text-nowrap">{{key}}</td>
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
                            <td><b>{{value.nivel_ocupacion}}%</b></td>
                            <td><b>{{value.nivel_ocupacion_backoffice}}%</b></td>
                        </tr>
                        </tbody>
                        <tbody v-if="selectedFilter == 1 && !loading">
                        <tr v-for="(v,k) in dataReport">
                            <td>
                                <table border='1'
                                       style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 12px'>
                                    <tr v-for="(vv,kk) in v">
                                        <td>{{kk}}</td>
                                        <td>
                                            <table border='1'
                                                   style='width: 100%;font-family: Helvetica, Arial, sans-serif;font-size: 12px'>
                                                <tr v-for="(vvv,kkk) in vv">
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
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Service from '../services/ReportService'
  import DatePicker from 'vue2-datepicker'
  import Multiselect from 'vue-multiselect'
  import moment from 'moment'
  import $ from 'jquery'

  export default {
    name: 'report',
    components: {Multiselect, DatePicker},
    data: () => ({
      moment: moment,
      loading: false,
      params: {
        tipo: 1800,
        fecha: '',
        user_id: '',
        rol: 'user',
      },
      dataReport: [],
      dataUsers: [],
      dataObj: {},
      model_date_1: '',
      model_date_2: '',
      selectedFilter: '0',
      selectedUser: null,
      selectedFecha: moment().format('YYYY-MM-DD HH:mm'),
      disabledFilter: false,
      checkedFilterTime: false,
    }),
    created () {
      this.getUsers()
    },
    methods: {
      cleanCheckeds(){
        this.checkedFilterTime = false;
      },
      getUsers () {
        Service.dispatch('getUsers', {self: this})
      },
      getReports () {
        Service.dispatch('getReports', {self: this})
      },
      refresh () {
        this.change()
      },
      change () {
        this.loading = true
        if (this.selectedUser != null) {
          this.dataReport = []
          if (this.selectedFilter === '0') {
            if (this.selectedFecha !== '' && this.selectedUser !== '' && this.params.rol !== '') {
              this.params.user_id = this.selectedUser.id
              this.params.fecha = moment(this.selectedFecha[0]).format('YYYY-MM-DD')+'/'+moment(this.selectedFecha[1]).format('YYYY-MM-DD')
              this.disabledFilter = true
              this.getReports()
            }
          } else {
            if (this.selectedFecha !== '') {
              this.params.user_id = 0
              this.params.fecha = moment(this.selectedFecha).format('YYYY-MM-DD')
              this.disabledFilter = true
              this.getReports()
            }
          }
        } else {
          this.loading = false
          this.params.user_id = ''
          this.selectedUser = null
          this.dataReport = []
        }
      },
      exportFile (ext, puser_id) {
        this.params.fecha = moment(this.selectedFecha[0]).format('YYYY-MM-DD')+'/'+moment(this.selectedFecha[1]).format('YYYY-MM-DD')
        if (puser_id !== undefined) {
          return window.open('/export?ext=' + ext + '&fecha=' + this.params.fecha + '&user_id=' + user_id + '&rol=' + this.params.rol + '&username=' + this.selectedUser.value)
        } else {
          return window.open('/export?ext=' + ext + '&fecha=' + this.params.fecha + '&user_id=' + this.params.user_id + '&rol=' + this.params.rol + '&username=' + this.selectedUser.value)
        }
      },
    },
  }
</script>

<style scoped>
</style>