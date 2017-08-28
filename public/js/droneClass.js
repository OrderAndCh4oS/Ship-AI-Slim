var droneClass = {
        kills: 0,
        position: null,
        velocity: null,
        thrust: null,
        thrusting: false,
        thrustPower: 0,
        speed: 0.1,
        turningSpeed: 1,
        angle: 0,
        colour: "#ff00ff",
    friction: 0.99,
        target: -1,
    droneObject: {},

    create: function (x, y, droneJson) {
            var obj = Object.create(this);
            obj.position = vector.create(x, y);
            obj.velocity = vector.create(0, 0);
            obj.velocity.setLength(0);
            obj.velocity.setAngle(Math.random() * Math.PI);
        obj.speed = (droneJson.thruster_power / 100) * 0.25 + 0.05;
        obj.turningSpeed = (droneJson.turning_speed / 100) * 0.7 + 0.3;
        obj.droneObject = droneJson;
            return obj;
        },

    accelerate: function (accelerate) {
        this.velocity.addTo(accelerate);
        },

    angleToPredictedLocation: function (drone) {
        var ghostDrone = vector.create(this.position.getX(), this.position.getY());
        ghostDrone.setLength(this.position.getLength());
        ghostDrone.setAngle(this.position.getAngle());
            this.velocity.multiplyBy(this.friction);
            this.setThrust();
        ghostDrone.addTo(this.thrust);
            return Math.atan2(
                ghostDrone.getY() - drone.position.getY(),
                ghostDrone.getX() - drone.position.getX()
            );
        },

        setThrust: function () {
            this.thrust = vector.create(0, 0);
            if (this.isThrusting()) {
                this.thrust.setLength(this.speed * this.thrustPower);
            } else {
                this.thrust.setLength(0);
            }
            this.thrust.setAngle(this.angle);
        },

        update: function () {
            this.velocity.multiplyBy(this.friction);
            this.position.addTo(this.velocity);
            this.setThrust();
            this.accelerate(this.thrust);
        },

        draw: function (context) {
            context.save();
            context.translate(this.position.getX(), this.position.getY());
            context.rotate(this.angle);
            context.beginPath();
            context.moveTo(10, 0);
            context.lineTo(-10, -7);
            context.lineTo(-10, 7);
            context.lineTo(10, 0);
            if (this.thrusting) {
                context.moveTo(-10, 0);
                context.lineTo(-15, 0);
            }
            context.strokeStyle = this.colour;
            context.stroke();
            context.fillStyle = this.colour;
            context.fill();
            context.font = "11px Verdana";
            context.fillText(this.kills, -12, -12);
            context.fillStyle = "#000";
            context.fillText(this.droneObject.id, -7, 5);
            context.restore();
        },

        drawExplosion: function (context) {
            context.save();
            context.translate(this.position.getX(),
                this.position.getY());
            context.beginPath();
            context.arc(0, 0, 20, 0, 2 * Math.PI);
            context.fillStyle = 'rgba(255, 107, 27, 0.7)';
            context.fill();
            context.restore();
        },

        angleTo: function (p2) {
            return Math.atan2(
                p2.position.getY() - this.position.getY(),
                p2.position.getX() - this.position.getX()
            );
        },

        distanceTo: function (p2) {
            var dx = p2.position.getX() - this.position.getX(),
                dy = p2.position.getY() - this.position.getY();
            return Math.sqrt(dx * dx + dy * dy);
        },

        incrementAngle: function (increment) {
            this.angle += increment;
        },

        startThrusting: function (thrustPower) {
            this.thrustPower = thrustPower;
            this.thrusting = true;
        },

        stopThrusting: function () {
            this.thrustPower = 0;
            this.thrusting = false;
        },

        isThrusting: function () {
            return this.thrusting;
        },

        turnLeft: function (turnSpeed) {
            var turn = turnSpeed * this.turningSpeed;
            this.incrementAngle(-turn);
        },

        turnRight: function (turnSpeed) {
            var turn = turnSpeed * this.turningSpeed;
            this.incrementAngle(turn);
        },

    loopDroneIfLeftDrawingArea: function (width, height) {
            if (this.position.getX() > width) {
                this.position.setX(0)
            }
            if (this.position.getX() < 0) {
                this.position.setX(width)
            }
            if (this.position.getY() > height) {
                this.position.setY(0)
            }
            if (this.position.getY() < 0) {
                this.position.setY(height)
            }
        },

    setDroneTurningSpeed: function (angleToClosest) {
            switch (true) {
                case (this.howClose(this.angle, angleToClosest) >= 0.6):
                    this.turnLeft(this.turningSpeed * 0.1);
                    break;
                case (this.howClose(this.angle, angleToClosest) >= 0.4):
                    this.turnLeft(this.turningSpeed * 0.1 / 3 * 2);
                    break;
                case (this.howClose(this.angle, angleToClosest) >= 0.2):
                    this.turnLeft(this.turningSpeed * 0.1 / 3);
                    break;
                case (this.howClose(this.angle, angleToClosest) > 0):
                    this.turnLeft(this.howClose(this.angle, angleToClosest));
                    break;
                case (this.howClose(this.angle, angleToClosest) <= -0.6):
                    this.turnRight(this.turningSpeed * 0.1);
                    break;
                case (this.howClose(this.angle, angleToClosest) <= -0.4):
                    this.turnRight(this.turningSpeed * 0.1 / 3 * 2);
                    break;
                case (this.howClose(this.angle, angleToClosest) <= -0.2):
                    this.turnRight(this.turningSpeed * 0.1 / 3);
                    break;
                case (this.howClose(this.angle, angleToClosest) < 0):
                    this.turnRight(this.howClose(this.angle, angleToClosest));
                    break;
            }
        },

        howClose: function (x, y) {
            return Math.atan2(Math.sin(x - y), Math.cos(x - y))
        },

    setDroneThrusters: function (angleToClosest) {
            if (this.howClose(this.angle, angleToClosest) <= 0.3 &&
                this.howClose(this.angle, angleToClosest) >= -0.3) {
                this.startThrusting(0.5)
            } else if (this.howClose(this.angle, angleToClosest) <= 0.15 &&
                this.howClose(this.angle, angleToClosest) >= -0.15) {
                this.startThrusting(0.8)
            } else if (this.howClose(this.angle, angleToClosest) <= 0.1 &&
                this.howClose(this.angle, angleToClosest) >= -0.1) {
                this.startThrusting(1)
            } else {
                this.stopThrusting()
            }
        },

    findClosestDrone: function (drone2, closestDrone, j, i) {
        var distance = this.distanceTo(drone2);
        if (!closestDrone.distance && i !== j) {
            closestDrone.angle = drone2.angleToPredictedLocation(this);
            closestDrone.distance = distance
            }
        if (distance < closestDrone.distance && i !== j) {
            closestDrone.angle = drone2.angleToPredictedLocation(this);
            closestDrone.distance = distance
            }
        }
    }
;
