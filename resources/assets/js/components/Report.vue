<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 m-auto">
                        <span class="card-title">Reporte por Hora</span>
                    </div>
                    <div class="col-6 m-auto"></div>
                </div>
                <hr>
                <div class="form-inline">
                    <date-picker v-model="selectedFecha"
                                 type="date"
                                 format="YYYY-MM-DD"
                                 lang="es"
                                 placeholder="Fecha"
                                 @input="change()"/>
                    <date-picker v-model="selectedFechaFin"
                                 type="date"
                                 format="YYYY-MM-DD"
                                 lang="es"
                                 placeholder="Fecha"
                                 @input="change()"/>
                    <multiselect v-model="selectedUser"
                                 selectedLabel="Seleccionado"
                                 deselectLabel="Remover"
                                 selectLabel="Seleccionar"
                                 placeholder="Buscar"
                                 :options="dataUsers"
                                 label="value"
                                 track-by="id"
                                 class="w-50"
                                 @input="change()"/>
                    <label>
                        <select v-model="params.prol" class="form-control" @change="change()">
                            <option value="user" selected>User</option>
                            <option value="backoffice" selected>BackOffice</option>
                        </select>
                    </label>
                    <div class="btn-group dropdown btn-group-xs" role="group" aria-label="Reserve Options">
                        <button @click="exportFile('xlsx')" :disabled="params.puser_id == '' " type="button"
                                class="btn btn-success" title="Exportar por defecto">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span>Excel</span>
                        </button>
                        <div class="btn-group open" role="group">
                            <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="true">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                                <li title="Exportar">
                                    <button @click="exportFile('xlsx')" :disabled="params.puser_id == '' "
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
                    <button class="btn btn-secondary" title="actualizar lista!" @click="refresh()">
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

  export default {
    name: 'report',
    components: {Multiselect, DatePicker},
    data: () => ({
      moment: moment,
      loading: false,
      params: {
        tipo: 1800,
        pfecha: '',
        puser_id: '',
        prol: 'user',
      },
      dataReport: [],
      dataUsers: [],
      dataObj: {},
      model_date_1: '',
      model_date_2: '',
      selectedFilter: '0',
      selectedUser: null,
      selectedFecha: moment().add(1,'days').format('YYYY-MM-DD'),
      selectedFechaFin: moment().add( 1,'days').format('YYYY-MM-DD'),
    }),
    created () {
      this.getUsers()
    },
    methods: {
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
            if (this.selectedFecha !== '' && this.selectedUser !== '' && this.params.prol !== '') {
              this.params.puser_id = this.selectedUser.id
              this.params.pfecha = moment(this.selectedFecha).format('YYYY-MM-DD')
              this.getReports()
            }
          } else {
            if (this.selectedFecha !== '') {
              this.params.puser_id = 0
              this.params.pfecha = moment(this.selectedFecha).format('YYYY-MM-DD')
              this.getReports()
            }
          }
        } else {
          this.loading = false
          this.params.puser_id = ''
          this.selectedUser = null
          this.dataReport = []
        }
      },
      exportFile (ext, puser_id) {
        this.params.pfecha = moment(this.selectedFecha).format('YYYY-MM-DD')
        if (puser_id !== undefined) {
          return window.open('/export?ext=' + ext + '&pfecha=' + this.params.pfecha + '&puser_id=' + puser_id + '&prol=' + this.params.prol + '&pusername=' + this.selectedUser.value)
        } else {
          return window.open('/export?ext=' + ext + '&pfecha=' + this.params.pfecha + '&puser_id=' + this.params.puser_id + '&prol=' + this.params.prol + '&pusername=' + this.selectedUser.value)
        }
      }
    }
  }
</script>

<style scoped>

</style>