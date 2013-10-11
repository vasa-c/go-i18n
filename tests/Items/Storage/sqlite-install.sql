CREATE TABLE "items" (
    "type" VARCHAR(20) NOT NULL,
    "lg" CHAR(2) NOT NULL,
    "cid" INT UNSIGNED NOT NULL DEFAULT 0,
    "cid_key" VARCHAR(20) NOT NULL DEFAULT "",
    "field" VARCHAR(20) NOT NULL,
    "value" VARCHAR(255) NULL DEFAULT NULL,
    "value_big" TEXT NULL DEFAULT NULL,
    PRIMARY KEY ("type","lg","cid","cid_key","field")
);

INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("news", "en", 10, "", "title", "#10 news title", NULL);
INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("news", "en", 11, "", "title", "#11 news title", NULL);
INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("news", "ru", 11, "", "title", "#11 zagolovok", NULL);
INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("news", "ru", 11, "", "text", NULL, "#11 text novosti");
INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("news", "ru", 12, "", "text", NULL, "#12 text novosti");
INSERT INTO "items" ("type", "lg", "cid", "cid_key", "field", "value", "value_big") VALUES ("pages", "en", 0, "main", "title", "Main page title", NULL);

