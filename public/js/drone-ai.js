startGame = function (squadronOneId, squadronTwoId) {

    var gameOver = false,
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        width = canvas.width = window.innerWidth,
        height = canvas.height = window.innerHeight,
        squadrons = [],
        drones = [],
        deadDrones = [],
        scores = scoreClass.create(),
        UI = UIClass.create(width, height),
        squadronCount = 0;

    getSquadron(squadronOneId);
    getSquadron(squadronTwoId);

    update();

    function getSquadron(id) {
        axios.get('/api/v1/squadrons/' + id)
            .then(function (response) {
                console.log(response);
                var squadron = response.data.data;
                createSquadron(squadron.drones);
                squadrons[squadronCount] = squadron;
                squadronCount++;
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function createSquadron(drones) {
        for (var i = 0; i < drones.length; i++) {
            instantiateDrone(drones[i]);
        }
    }

    function updateSquadronData() {
        var i;
        for (i = 0; i < drones.length; i++) {

        }

        for (i = 0; i < deadDrones.length; i++) {

        }
    }

    function update() {
        UI.drawBackground(context);
        UI.showUI(scores, context);

        for (var i = drones.length - 1; i >= 0; i--) {
            var drone = drones[i],
                closestDrone = {
                    distance: false,
                    angle: 0
                };

            for (var j = drones.length - 1; j >= 0; j--) {
                if (containsDeadDrone(i, j)) {
                    continue;
                }
                if (drones[j].colour !== drone.colour) {
                    drone.findClosestDrone(drones[j], closestDrone, j, i);
                    detectDroneCollision(closestDrone.distance, i, j);
                }
            }

            drone.setDroneTurningSpeed(closestDrone.angle);
            drone.setDroneThrusters(closestDrone.angle);

            drone.update();
            drone.draw(context);
            drone.loopDroneIfLeftDrawingArea(width, height);
        }

        if (UI.countdown() === 0) {
            gameOver = true;
        }

        removeDeadDrones();
        if (!gameOver) {
            requestAnimationFrame(update)
        } else {
            updateSquadronData();
        }
    }

    function isInArray(value, array) {
        return array.indexOf(value) > -1
    }

    function containsDeadDrone(i, j) {
        return isInArray(i, deadDrones) || isInArray(j, deadDrones);
    }

    function setDroneColour(drone) {
        switch (squadronCount % 6) {
            case 0:
                drone.colour = '#ff3243';
                break;
            case 1:
                drone.colour = '#61ff83';
                break;
            case 2:
                drone.colour = '#ff4fa9';
                break;
            case 3:
                drone.colour = '#6ddfff';
                break;
            case 4:
                drone.colour = '#ffe76a';
                break;
            case 5:
                drone.colour = '#4b6aff';
                break;
        }
    }

    function instantiateDrone(droneJson) {
        var drone = droneClass.create(Math.random() * (width),
            Math.random() * (height), droneJson);
        setDroneColour(drone);
        drone.angle = squadronCount % 2 === 0 ? 0 : Math.PI;
        drones.push(drone);
        return drone;
    }

    function detectDroneCollision(distance, i, j) {
        if (distance < 12 && i !== j && !isInArray(i, deadDrones) && !isInArray(j, deadDrones)) {
            if (Math.random >= 0.5) {
                deadDrones.push(j);
                drones[i].kills++;
                scores.incrementScore(drones[i].colour);
            } else {
                deadDrones.push(i);
                drones[j].kills++;
                scores.incrementScore(drones[j].colour);
            }
        }
    }

    function removeDeadDrones() {
        deadDrones.sort();
        for (var k = deadDrones.length - 1; k >= 0; k--) {
            var index = deadDrones[k];
            drones[index].drawExplosion(context);
            drones.splice(index, 1);
            deadDrones.pop()
        }
    }
};

startGame(1, 2);