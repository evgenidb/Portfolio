var student1 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student1.init("Student1", "Pesho", 13, 1);
var student2 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student2.init("Student2", "Maria", 23, 2);
var student3 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student3.init("Student3", "Gosho", 33, 3);
var student4 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student4.init("Student4", "Stancho", 43, 4);
var student5 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student5.init("Student5", "Eliza", 53, 5);
var student6 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student6.init("Student6", "Ceca", 63, 6);
var student7 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student7.init("Student7", "Toto", 73, 7);
var student8 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student8.init("Student8", "Nikita", 83, 8);
var student9 = Object.create(SchoolRepositoryPrototypeOOP.Student);
student9.init("Student9", "Veli", 93, 9);


var teacher1 = Object.create(SchoolRepositoryPrototypeOOP.Teacher);
teacher1.init("Teacher1", "Dancho", 113, "Math");
var teacher2 = Object.create(SchoolRepositoryPrototypeOOP.Teacher);
teacher2.init("Teacher2", "Penka", 123, "Science");

var class1 = Object.create(SchoolRepositoryPrototypeOOP.SchoolClass);
class1.init("The Stupids", teacher1, 4);
var class2 = Object.create(SchoolRepositoryPrototypeOOP.SchoolClass);
class2.init("The Morons", teacher2, 4);


class1.addStudent(student1);
class1.addStudent(student2);
class1.addStudent(student3);
class1.addStudent(student4);


class2.addStudent(student5);
class2.addStudent(student6);
class2.addStudent(student7);
class2.addStudent(student8);

try {
    class2.addStudent(student9);
}
catch (ex) {
    console.log(ex.message);
}

var school = Object.create(SchoolRepositoryPrototypeOOP.School);
school.init("Foobar School", "Foobared Town");
school.addClass(class1);
school.addClass(class2);


console.log(school.toString());
console.log(school.toJSON());