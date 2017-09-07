Vue.component('drone-list', {
    props: ['drones', 'form'],

    template: `
        <table>
            <tr>
                <th>Name</th>
                <th>Thruster Power</th>
                <th>Turning Speed</th>
                <th>Kills</th>
            </tr>
            <template v-for="drone in form.drones">
                <update-drone-item :drone="drone"></update-drone-item>
            </template>
        </table>
    `
});