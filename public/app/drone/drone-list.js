Vue.component('drone-list', {
    props: ['drones', 'form'],

    template: `
        <table>
            <tr>
                <th>Name</th>
                <th>Thruster Power</th>
                <th>Turning Speed</th>
            </tr>
            <template v-for="(drone, index) in form.drones">
                <update-drone-item :drone="drone" :index="index"></update-drone-item>
            </template>
        </table>
    `
});