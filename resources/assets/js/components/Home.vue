<template>
    <div>
        <div class="alert alert-secondary">
            <h4>{{data_auth.primer_nombre}} {{data_auth.apellido_paterno}},</h4>
            <p></p>
            <div class="pl-3">
                <p>El presente software cumple la complementación del sistema de centrales de Asterisk, en el apoyo con
                    los
                    reportes de
                    tal manera que éste sea el unico propósito y objetivo de mejora hasta el momento.</p>
                <b>Capacidades:</b>
                <ul>
                    <li>Filtrar dinamicamente.</li>
                    <li>Buscar por usuario.</li>
                    <li>Exportar por usuario y usuarios.</li>
                </ul>
                <b>Tecnologia:</b>
                <ul>
                    <li>Experiencia de usuario con un solo <b>toke</b>.</li>
                    <li>Experiencia de usuario en las notificaciones.</li>
                </ul>
            </div>
        </div>
        <table style="width: 100%">
            <tr v-for="(v,k) in data_about">
                <template v-if="k%2 === 0">
                    <td v-if="k < data_about.length" width="50%">
                        <div class="img-thumbnail">
                            <img style="border-radius: 4px" :src="'img/about/'+v" width="100%">
                        </div>
                    </td>
                    <td v-if="k + 1  < data_about.length" width="50%">
                        <div class="img-thumbnail">
                            <img style="border-radius: 4px" :src="'img/about/'+data_about[k+1]" width="100%">
                        </div>
                    </td>
                </template>
            </tr>
        </table>
    </div>
</template>

<script>
  export default {
    name: 'home',
    data: () => ({
      data_auth: {},
      data_about: {}
    }),
    created () {
      this.data_auth = JSON.parse(window.localStorage.getItem('data_auth'))
      this.data_about = ['1.png', '2.png', '3.png', '4.png', '5.png']
    },
    methods: {
      getImgUrl (image) {
        // return require('../../assets/img/about/' + image);
      },
    }
  }
</script>

<style scoped>

</style>