/*Please execute the code below in MySQL on your server*/
CREATE DATABASE quizmanager;
CREATE TABLE Quiz (
    quiz_id int NOT NULL AUTO_INCREMENT,
    quiz_name varchar(45) NOT NULL,
    PRIMARY KEY (quiz_id)
);

CREATE TABLE Questions (
    question_id int NOT NULL AUTO_INCREMENT,
    question varchar(100) NOT NULL,
    quiz_id int,
    answer_one varchar(45) NOT NULL,
    answer_two varchar(45) NOT NULL,
    answer_three varchar(45) NOT NULL,
    answer_four varchar(45),
    answer_five varchar(45),
    PRIMARY KEY (question_id),
    FOREIGN KEY (quiz_id) REFERENCES Quiz(quiz_id) ON DELETE CASCADE
);

CREATE TABLE Users (
    user_id int NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    password varchar(255) NOT NULL,
    permission INT NOT NULL,
    PRIMARY KEY (user_id)
);
