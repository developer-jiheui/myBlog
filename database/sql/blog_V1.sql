-- DROP the database if it exists
-- CREATE THE DATABASE
DROP DATABASE IF EXISTS blog;
CREATE DATABASE IF NOT EXISTS blog;

-- Use the database
USE blog;

-- drop in case the table exists.
DROP TABLE IF EXISTS USER;
DROP TABLE IF EXISTS ACCESS_HISTORY;
DROP TABLE IF EXISTS BLOG;
DROP TABLE IF EXISTS COMMENT;
DROP TABLE IF EXISTS PORTFOLIO;
DROP TABLE IF EXISTS LIKES;

-- user table
CREATE TABLE USER (
                        USER_ID INT NOT NULL AUTO_INCREMENT,
                        EMAIL VARCHAR(100) NOT NULL UNIQUE,
                        AVATAR VARCHAR(100),
                        PW VARCHAR(64),
                        USER_TYPE INT,# admin 0, common user 1
                        FIRST_NAME VARCHAR(100),
                        LAST_NAME VARCHAR(100),
                        REGISTER_TYPE INT, #for api
                        REGISTER_DT DATETIME,
                        ADDRESS VARCHAR(100),
                        PHONE_NUM VARCHAR(20),
                        BIO VARCHAR(1024),
                        JOB_TITLE VARCHAR(100),
                        BIRTHDAY DATE,
                        INSTAGRAM_URL VARCHAR(100),
                        LINKEDIN_URL VARCHAR(100),
                        GITHUB_URL VARCHAR(100),
                        PRIMARY KEY (USER_ID)
);
CREATE TABLE ACCESS_HISTORY (
                                  ACCESS_HISTORY_NO INT NOT NULL,
                                  EMAIL VARCHAR(100),
                                  IP VARCHAR(50),
                                  SESSION_ID VARCHAR(32),
                                  SIGNIN_DT DATE,
                                  SIGNOUT_DT DATE,
                                  PRIMARY KEY (ACCESS_HISTORY_NO),
                                  CONSTRAINT FK_ACCESS_HISTORY_USER FOREIGN KEY (EMAIL)
                                      REFERENCES USER(EMAIL) ON DELETE CASCADE
);

CREATE TABLE BLOG (
                        BLOG_ID INT NOT NULL AUTO_INCREMENT,
                        TITLE VARCHAR(1000) NOT NULL,
                        CONTENTS TEXT,
                        USER_ID INT NOT NULL,
                        CREATE_DT TIMESTAMP NULL,
                        MODIFY_DT TIMESTAMP NULL,
                        IMAGE_URL VARCHAR(100),
                        PRIMARY KEY (BLOG_ID),
                        CONSTRAINT FK_BLOG_USER FOREIGN KEY (USER_ID)
                            REFERENCES USER(USER_ID) ON DELETE CASCADE
);
CREATE TABLE COMMENT (
                           COMMENT_ID INT NOT NULL AUTO_INCREMENT,
                           CONTENTS VARCHAR(4000) NOT NULL,
                           CREATE_DT TIMESTAMP NULL,
                           STATE INT,                    -- 0: deleted, 1: normal, 2: edited
                           DEPTH INT,                    -- 0: original comment, 1: reply, 2: sub-reply
                           GROUP_NO INT,                     -- All replies to the same original comment share the same GROUP_NO
                           USER_ID INT,
                           BLOG_ID INT,
                           PARENT_ID INT,
                           PRIMARY KEY (COMMENT_ID),
                           CONSTRAINT FK_COMMENT_USER FOREIGN KEY (USER_ID)
                               REFERENCES USER(USER_ID),
                           CONSTRAINT FK_COMMENT_BLOG FOREIGN KEY (BLOG_ID)
                               REFERENCES BLOG(BLOG_ID) ON DELETE CASCADE
);

CREATE TABLE PORTFOLIO (
    PORTFOLIO_ID INT NOT NULL AUTO_INCREMENT,
    USER_ID INT,
    TITLE VARCHAR(100),
    DESCRIPTION TEXT,
    CATEGORY VARCHAR(20),
    PROJECT_URL VARCHAR(1024),
    CREATED_AT DATE,
    UPDATED_AT DATE,
    IMAGE_URL VARCHAR(1024),
    LIKE_COUNT INT,
    PRIMARY KEY (PORTFOLIO_ID),
    FOREIGN KEY (USER_ID) REFERENCES USER(USER_ID)
);

CREATE TABLE LIKES (
    LIKE_ID INT NOT NULL AUTO_INCREMENT,
    PORTFOLIO_ID INT,
    USER_ID INT,
    PRIMARY KEY (LIKE_ID),
    FOREIGN KEY (PORTFOLIO_ID) REFERENCES PORTFOLIO(PORTFOLIO_ID) ON DELETE CASCADE,
    FOREIGN KEY (USER_ID) REFERENCES USER(USER_ID)
);

