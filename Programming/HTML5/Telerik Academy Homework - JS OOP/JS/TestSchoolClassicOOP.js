var student1 = new SchoolRepositoryClassicalOOP.Student("Student1", "Pesho", 13, 1);
var student2 = new SchoolRepositoryClassicalOOP.Student("Student2", "Maria", 23, 2);
var student3 = new SchoolRepositoryClassicalOOP.Student("Student3", "Gosho", 33, 3);
var student4 = new SchoolRepositoryClassicalOOP.Student("Student4", "Stancho", 43, 4);
var student5 = new SchoolRepositoryClassicalOOP.Student("Student5", "Eliza", 53, 5);
var student6 = new SchoolRepositoryClassicalOOP.Student("Student6", "Ceca", 63, 6);
var student7 = new SchoolRepositoryClassicalOOP.Student("Student7", "Toto", 73, 7);
var student8 = new SchoolRepositoryClassicalOOP.Student("Student8", "Nikita", 83, 8);
var student9 = new SchoolRepositoryClassicalOOP.Student("Student9", "Veli", 93, 9);


var teacher1 = new SchoolRepositoryClassicalOOP.Teacher("Teacher1", "Dancho", 113, "Math");
var teacher2 = new SchoolRepositoryClassicalOOP.Teacher("Teacher2", "Penka", 123, "Science");

var class1 = new SchoolRepositoryClassicalOOP.SchoolClass("The Stupids", teacher1, 4);
var class2 = new SchoolRepositoryClassicalOOP.SchoolClass("The Morons", teacher2, 4);


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
catch (ex){
    console.log(ex.message);
}

var school = new SchoolRepositoryClassicalOOP.School("Foobar School", "Foobared Town");
school.addClass(class1);
school.addClass(class2);


console.log(school.toString());
console.log(school.toJSON());