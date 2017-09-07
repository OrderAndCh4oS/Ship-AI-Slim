Vue.component('update-drone-item', {
    props: ['drone'],

    template: `
        <tr>
            <td>
                <input type="hidden" :name="formName('id')" :value="drone.id" v-model="drone.id">
                <input name="formName('name')" title="Name" :value="drone.name" v-model="drone.name">
            </td>
            <td>
                <input :name="formName('thruster_power')" 
                       type="number" 
                       title="Thruster Power" 
                       :value="drone.thruster_power" 
                       :min="drone.thruster_power" 
                       max="100" 
                       step="1"
                       v-model="drone.thruster_power">
            </td>
            <td>
                <input :name="formName('turning_speed')" 
                       type="number" 
                       title="Turning Speed" 
                       :value="drone.turning_speed" 
                       :min="drone.turning_speed" 
                       max="100" 
                       step="1"
                       v-model="drone.turning_speed">
            </td>
            <td>
                {{drone.kills}}
            </td>
        </tr>
    `,
    methods: {
        formName(field) {
            return 'drone[' + this.index + '][' + field + ']'
        }
    }
});
