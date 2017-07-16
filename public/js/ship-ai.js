window.onload = function () {
    var canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        width = canvas.width = window.innerWidth,
        height = canvas.height = window.innerHeight,
        ships = [],
        deadShips = [],
        scores = scoreClass.create(),
        UI = UIClass.create(width, height);

    for (var i = 90; i > 0; i--) {
        instantiateShip((Math.random() * 100), (Math.random() * 100));
    }

    update();

    function update() {
        UI.drawBackground(context);
        UI.showUI(scores, context);

        for (var i = ships.length - 1; i >= 0; i--) {
            var ship = ships[i],
                closestShip = {
                    distance: false,
                    angle: 0
                };

            for (var j = ships.length - 1; j >= 0; j--) {
                if (containsDeadShip(i, j)) {
                    continue;
                }
                if (ships[j].colour !== ship.colour) {
                    ship.findClosestShip(ships[j], closestShip, j, i);
                    detectShipCollision(closestShip.distance, i, j);
                }
            }

            ship.setShipTurningSpeed(closestShip.angle);
            ship.setShipThrusters(closestShip.angle);

            ship.update();
            ship.draw(context);
            ship.loopShipIfLeftDrawingArea(width, height);
        }

        removeDeadShips();

        requestAnimationFrame(update)
    }

    function isInArray(value, array) {
        return array.indexOf(value) > -1
    }

    function containsDeadShip(i, j) {
        return isInArray(i, deadShips) || isInArray(j, deadShips);
    }

    function setShipColour(ship) {
        switch (i % 6) {
            case 0:
                ship.colour = '#ff3243';
                break;
            case 1:
                ship.colour = '#ff4fa9';
                break;
            case 2:
                ship.colour = '#61ff83';
                break;
            case 3:
                ship.colour = '#6ddfff';
                break;
            case 4:
                ship.colour = '#4b6aff';
                break;
            case 5:
                ship.colour = '#ffe76a';
                break;
        }
    }

    function instantiateShip(thrusterPower, turningSpeed) {
        var ship = shipClass.create(Math.random() * (width),
            Math.random() * (height), (thrusterPower / 100) * 0.25 + 0.05,
            (turningSpeed / 100) * 0.7 + 0.3);
        ship.friction = 0.99;
        setShipColour(ship);
        ship.id = i;
        ship.angle = i % 2 === 0 ? 0 : Math.PI;
        ships.push(ship);
        return ship;
    }

    function detectShipCollision(distance, i, j) {
        if (distance < 12 && i !== j && !isInArray(i, deadShips) && !isInArray(j, deadShips)) {
            if (Math.random >= 0.5) {
                deadShips.push(j);
                ships[i].kills++;
                scores.incrementScore(ships[i].colour);
            } else {
                deadShips.push(i);
                ships[j].kills++;
                scores.incrementScore(ships[j].colour);
            }
        }
    }

    function removeDeadShips() {
        deadShips.sort();
        for (var k = deadShips.length - 1; k >= 0; k--) {
            var index = deadShips[k];
            ships[index].drawExplosion(context);
            ships.splice(index, 1);
            deadShips.pop()
        }
    }
};