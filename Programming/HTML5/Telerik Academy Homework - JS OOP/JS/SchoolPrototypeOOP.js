var SchoolRepositoryPrototypeOOP = (function SchoolRepositoryPrototypeOOP () {
    var Person = {
        init: function init(firstName, lastName, age) {
            this.firstName = firstName;
            this.lastName = lastName;
            this.age = age;
        },

        getFullName: function getFullName() {
            return this.firstName + " " + this.lastName;
        },

        toString: function toString () {
            return JSON.stringify(this.toJSON());
        },

        toJSON: function toJSON() {
            return {
                firstName: this.firstName,
                lastName: this.lastName,
                age: this.age
            };
        }
    };

    var Student = Person.extend({
        init: function init(firstName, lastName, age, grade) {
            this._super.init(firstName, lastName, age);
            this.grade = grade;
        },

        introduce: function introduce() {
            return this._super.toString() + ", Grade: " + this.grade;
        },

        toJSON: function toJSON() {
            var json = this._super.toJSON();
            json.grade = this.grade;

            return json;
        }
    });

    var Teacher = Person.extend({
        init: function init(firstName, lastName, age, speciality) {
            this._super.init(firstName, lastName, age);
            this.speciality = speciality;
        },

        introduce: function introduce() {
            return this._super.toString() + ", Speciality: " + this.speciality;
        },

        toJSON: function toJSON() {
            var json = this._super.toJSON();
            json.speciality = this.speciality;

            return json;
        }
    });


    var SchoolClass = {
        init: function init(name, formTeacher, classCapacity) {
            this.name = name;
            this.formTeacher = formTeacher;
            this.classCapacity = classCapacity;
            this.students = [];
        },

        addStudent: function addStudent (student) {

            if ((this.students.length + 1) <= this.classCapacity) {
                this.students.push(student);
            }
            else {
                throw {
                    message: "Cannot add student. The class is full",
                    classCapacity: this.classCapacity,
                    studentInClass: this.students.length
                };
            }
        },

        removeStudent: function removeStudent (student) {
            var indexToRemove = this.students.indexOf(student);
            this.students.splice(indexToRemove, 1);
        },

        toString: function toString () {
            return JSON.stringify(this.toJSON());
        },

        toJSON: function toJSON() {
            var jsonStudents = [];

            var studentIndex;
            var jsonStudentInfo;
            for (studentIndex = 0; studentIndex < this.students.length; studentIndex++) {
                jsonStudentInfo = this.students[studentIndex].toJSON();
                jsonStudents.push(jsonStudentInfo);
            }

            return {
                name: this.name,
                formTeacher: this.formTeacher.toJSON(),
                classCapacity: this.classCapacity,
                students: jsonStudents
            };
        }
    };

    var School = {
        init: function init(name, town) {
            this.name = name;
            this.town = town;
            this.classes = [];
        },

        addClass: function addClass (schoolClass) {
            this.classes.push(schoolClass);
        },

        removeClass: function removeClass (schoolClass) {
            var indexToRemove = this.classes.indexOf(schoolClass);
            this.classes.splice(indexToRemove, 1);
        },

        toString: function toString () {
            return JSON.stringify(this.toJSON());
        },

        toJSON: function toJSON() {
            var jsonClasses = [];

            var classIndex;
            var jsonClassInfo;
            for (classIndex = 0; classIndex < this.classes.length; classIndex++) {
                jsonClassInfo = this.classes[classIndex].toJSON();
                jsonClasses.push(jsonClassInfo);
            }

            return {
                name: this.name,
                town: this.town,
                classes: jsonClasses
            };
        }
    };

    return {
        Student: Student,
        Teacher: Teacher,
        SchoolClass: SchoolClass,
        School: School
    };
}());