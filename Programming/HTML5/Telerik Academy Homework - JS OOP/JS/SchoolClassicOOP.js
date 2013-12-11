var SchoolRepositoryClassicalOOP = (function SchoolRepositoryClassicalOOP () {
    var Person = Class.create({
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
    });

    var Student = Class.create({
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

    Student.inherit(Person);

    var Teacher = Class.create({
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

    Teacher.inherit(Person);


    var SchoolClass = Class.create({
        init: function init(name, formTeacher, classCapacity) {
            this.name = name;
            this.formTeacher = formTeacher;
            this.classCapacity = classCapacity;
            this.students = [];
        },

        addStudent: function addStudent (student) {
            if (student instanceof Student) {
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
            }
            else {
                throw {
                    message: "Incorrect type of argument",
                    argumentName: "student",
                    valuePassed: student
                };
            }
        },

        removeStudent: function removeStudent (student) {
            if (student instanceof Student) {
                var indexToRemove = this.students.indexOf(student);
                this.students.splice(indexToRemove, 1);
            }
            else {
                throw {
                    message: "Incorrect type of argument",
                    argumentName: "student",
                    valuePassed: student
                };
            }
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
    });

    var School = Class.create({
        init: function init(name, town) {
            this.name = name;
            this.town = town;
            this.classes = [];
        },

        addClass: function addClass (schoolClass) {
            if (schoolClass instanceof SchoolClass) {
                this.classes.push(schoolClass);
            }
            else {
                throw {
                    message: "Incorrect type of argument",
                    argumentName: "schoolClass",
                    valuePassed: schoolClass
                };
            }
        },

        removeClass: function removeClass (schoolClass) {
            if (schoolClass instanceof SchoolClass) {
                var indexToRemove = this.classes.indexOf(schoolClass);
                this.classes.splice(indexToRemove, 1);
            }
            else {
                throw {
                    message: "Incorrect type of argument",
                    argumentName: "schoolClass",
                    valuePassed: schoolClass
                };
            }
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
    });

    return {
        Student: Student,
        Teacher: Teacher,
        SchoolClass: SchoolClass,
        School: School
    };
}());