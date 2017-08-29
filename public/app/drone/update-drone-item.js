Vue.component('update-drone-item', {
    props: ['drone'],

    template: `
        <tr>
            <td>
                <input :form="'drone-' + drone.id" name="name" title="Name" :value="drone.name">
            </td>
            <td>
                <input :form="'drone-' + drone.id" name="thruster_power" type="number" title="Thruster Power" :value="drone.thrusterPower" :min="drone.thrusterPower" max="100" step="1">
            </td>
            <td>
                <input :form="'drone-' + drone.id" name="turning_speed" type="number" title="Turning Speed" :value="drone.turningSpeed" :min="drone.turningSpeed" max="100" step="1">
            </td>
            <td>
                <form action="/manage-drones" method="post" id="'drone-' + drone.id">
                    <input type="hidden" name="id" :value="drone.id">
                    <input type="submit" name="submit" id="submit" value="Submit">
                </form>
            </td>
        </tr>
    `
});
