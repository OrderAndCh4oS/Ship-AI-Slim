Vue.component('drone-list', {
    props: ['drones'],

    template: `
        <table>
            <tr>
                <th>Name</th>
                <th>Thruster Power</th>
                <th>Turning Speed</th>
                <th>Save</th>
            </tr>
            <update-drone-item v-for="drone in drones" :drone="drone"></update-drone-item>
        </table>
    `
});