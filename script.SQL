/*
Created: 20/02/2021
Modified: 21/08/2021
Project: studentAchievements
Model: PostgreSQL 10
Company: /
Author: dropRole
Version: 1
Database: PostgreSQL 10
*/


-- Create roles section -------------------------------------------------

CREATE ROLE 
    authorized 
WITH
    SUPERUSER
    INHERIT
    LOGIN
    CONNECTION LIMIT 1;

GRANT 
    SELECT, 
    INSERT, 
    UPDATE, 
    DELETE, 
    REFERENCES
ON TABLE 
    students, 
    scientific_papers, 
    attendances, 
    graduations, 
    partakings, 
    certificates, 
    mentorings, 
    documents, 
    residences, 
    accounts
TO 
    authorized WITH GRANT OPTION;

GRANT 
    SELECT, 
    REFERENCES 
ON TABLE 
    faculties, 
    programs, 
    postal_codes, 
    countries 
TO 
    authorized;

GRANT 
    ALL PRIVELEGES 
ON SEQUENCE 
    students_id_students_seq, 
    scientific_papers_id_scientific_papers_seq,
    attendances_id_attendances_seq, 
    certificates_id_certificates_seq,
    graduations_id_graduations_seq,
    partakings_id_partakings_seq,
    mentorings_id_mentorings_seq,
    documents_id_documents_seq,
    residences_id_residences_seq,
    accounts_id_accounts_seq
TO 
    authorized;

GRANT 
    SELECT
ON SEQUENCE 
    faculties_id_faculties_seq, 
    programs_id_programs_seq, 
    postal_codes_id_postal_codes_seq, 
    countries_id_countries_seq
TO 
    authorized;
;

CREATE ROLE 
    student 
WITH 
    LOGIN;
    
GRANT 
    SELECT, 
    REFERENCES 
ON TABLE 
    faculties, 
    programs, 
    postal_codes, 
    countries 
TO 
    student;

GRANT 
    SELECT, 
    INSERT, 
    UPDATE, 
    DELETE, 
    REFERENCES 
ON TABLE 
    scientific_papers, 
    partakings, 
    mentorings, 
    documents, 
    residences
TO  
    student;

GRANT 
    SELECT 
ON TABLE 
    attendances, 
    graduations, 
    certificates
TO 
    student;

GRANT 
    SELECT, 
    UPDATE
ON TABLE 
    students,
    accounts
TO 
    student;

GRANT 
    ALL PRIVILEGES 
ON SEQUENCE 
    students_id_students_seq
    scientific_papers_id_scientific_papers_seq,
    partakings_id_partakings_seq, 
    mentorings_id_mentorings_seq, 
    documents_id_documents_seq, 
    residences_id_residences_seq
TO 
    student;

GRANT 
    USAGE 
ON SEQUENCE 
    faculties_id_faculties_seq, 
    programs_id_programs_seq, 
    postal_codes_id_postal_codes_seq, 
    countries_id_countries_seq,
    attendances_id_attendances_seq,  
    certificates_id_certificates_seq 
TO 
    student;
;

CREATE ROLE 
    visitor;

GRANT 
    SELECT, 
    REFERENCES 
ON TABLE 
    students, 
    scientific_papers, 
    attendances, 
    graduations, 
    certificates, 
    partakings, 
    mentorings, 
    documents, 
    faculties, 
    programs, 
    postal_codes, 
    countries 
TO 
    visitor;
    
GRANT 
    USAGE 
ON SEQUENCE 
    students_id_students_seq, 
    scientific_papers_id_scientific_papers_seq, 
    attendances_id_attendances_seq,  
    certificates_id_certificates_seq, 
    partakings_id_partakings_seq, 
    mentorings_id_mentorings_seq, 
    documents_id_documents_seq, 
    faculties_id_faculties_seq, 
    programs_id_programs_seq, 
    postal_codes_id_postal_codes_seq, 
    countries_id_countries_seq 
TO 
    visitor;
;

CREATE USER 
    auth_69141238         
WITH 
    PASSWORD '$2a$06$KfAPVfdakTSzQVeKYeIiruMUOa2yKNuaATqYvgi/.msBVoZwqo.7a'
    IN ROLE authorized    
    VALID UNTIL 'infinity';
;

-- Create tables section -------------------------------------------------

-- Table students

CREATE TABLE "students"(
 "id_students" Serial NOT NULL,
 "id_postal_codes" Integer,
 "name" Character varying(20) NOT NULL,
 "surname" Character varying(40) NOT NULL,
 "email" Character varying(40),
 "telephone" Character varying(13)
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table students

CREATE INDEX "IX_students_id_postal_codes" ON "students" ("id_postal_codes")
;

-- Add keys for table students

ALTER TABLE "students" ADD CONSTRAINT "PK_students" PRIMARY KEY ("id_students")
;

-- Table scientific_papers

CREATE TABLE "scientific_papers"(
 "id_scientific_papers" Serial NOT NULL,
 "id_attendances" Integer,
 "type" Character varying(17) NOT NULL
        CONSTRAINT "sctype_check" CHECK ("type" IN('SEMINARKSA DELO', 'RAZISKOVALNO DELO', 'DIPLOMSKO DELO', 'MAGISTERSKO DELO', 'DOKTORSKO DELO')),
 "topic" Character varying(60) NOT NULL,
 "written" Date NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table scientific_papers

CREATE INDEX "IX_scientific_papers_id_attendances" ON "scientific_papers" ("id_attendances")
;

-- Add keys for table scientific_papers

ALTER TABLE "scientific_papers" ADD CONSTRAINT "PK_scientific_papers" PRIMARY KEY ("id_scientific_papers")
;

-- Table documents

CREATE TABLE "documents"(
 "id_documents" Serial NOT NULL,
 "id_scientific_papers" Integer NOT NULL,
 "source" Text NOT NULL,
 "published" Date NOT NULL,
 "version" Character varying(3)
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table documents

CREATE INDEX "IX_documents_id_scientific_papers" ON "documents" ("id_scientific_papers")
;

-- Add keys for table documents

ALTER TABLE "documents" ADD CONSTRAINT "PK_documents" PRIMARY KEY ("id_documents")
;

-- Table faculties

CREATE TABLE "faculties"(
 "id_faculties" Serial NOT NULL,
 "id_postal_codes" Integer NOT NULL,
 "name" Character varying(80) NOT NULL,
 "address" Character varying(40) DEFAULT 40 NOT NULL,
 "email" Character varying(20) DEFAULT 40 NOT NULL,
 "telephone" Character varying(16) NOT NULL,
 "dean" Character varying(30) DEFAULT 60 NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table faculties

CREATE INDEX "IX_faculties_id_postal_codes" ON "faculties" ("id_postal_codes")
;

-- Add keys for table faculties

ALTER TABLE "faculties" ADD CONSTRAINT "PK_faculties" PRIMARY KEY ("id_faculties")
;

-- Table programs

CREATE TABLE "programs"(
 "id_programs" Serial NOT NULL,
 "id_faculties" Integer,
 "name" Character varying(80) NOT NULL,
 "degree" Character varying(6) NOT NULL
        CONSTRAINT "degree_check" CHECK ("degree" IN('VI/2', 'VII', 'VIII/2')),
 "duration" Character(1) NOT NULL,
 "field" Character varying(80)
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table programs

CREATE INDEX "IX_programs_id_faculties" ON "programs" ("id_faculties")
;

-- Add keys for table programs

ALTER TABLE "programs" ADD CONSTRAINT "PK_programs" PRIMARY KEY ("id_programs")
;

-- Table certificates

CREATE TABLE "certificates"(
 "id_certificates" Serial NOT NULL,
 "source" Text NOT NULL,
 "issued" Date NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Add keys for table certificates

ALTER TABLE "certificates" ADD CONSTRAINT "PK_certificates" PRIMARY KEY ("id_certificates")
;

-- Table postal_codes

CREATE TABLE "postal_codes"(
 "id_postal_codes" Serial NOT NULL,
 "id_countries" Integer NOT NULL,
 "municipality" Character varying(40) NOT NULL,
 "code" Character varying(8) NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table postal_codes

CREATE INDEX "IX_postal_codes_id_countries" ON "postal_codes" ("id_countries")
;

-- Add keys for table postal_codes

ALTER TABLE "postal_codes" ADD CONSTRAINT "PK_postal_codes" PRIMARY KEY ("id_postal_codes")
;

-- Table countries

CREATE TABLE "countries"(
 "id_countries" Serial NOT NULL,
 "name" Character varying(30) NOT NULL,
 "iso_3_code" Character(3) NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Add keys for table countries

ALTER TABLE "countries" ADD CONSTRAINT "PK_countries" PRIMARY KEY ("id_countries")
;

ALTER TABLE "countries" ADD CONSTRAINT "iso_3_code" UNIQUE ("iso_3_code")
;

-- Table accounts

CREATE TABLE "accounts"(
 "id_attendances" Integer NOT NULL,
 "pass" Character varying(60) NOT NULL,
 "granted" Date NOT NULL,
 "avatar" Text DEFAULT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Add keys for table accounts

ALTER TABLE "accounts" ADD CONSTRAINT "PK_accounts" PRIMARY KEY ("id_attendances")
;

-- Table attendances

CREATE TABLE "attendances"(
 "id_attendances" Serial NOT NULL,
 "id_students" Integer NOT NULL,
 "id_faculties" Integer NOT NULL,
 "id_programs" Integer NOT NULL,
 "enrolled" Date NOT NULL,
 "index" Character varying(11) NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table attendances

CREATE INDEX "IX_attendances_id_students" ON "attendances" ("id_students")
;

CREATE INDEX "IX_attendances_id_faculties" ON "attendances" ("id_faculties")
;

CREATE INDEX "IX_attendances_id_programs" ON "attendances" ("id_programs")
;

-- Add keys for table attendances

ALTER TABLE "attendances" ADD CONSTRAINT "PK_attendances" PRIMARY KEY ("id_attendances")
;

-- Table graduations

CREATE TABLE "graduations"(
 "id_certificates" Integer NOT NULL,
 "id_attendances" Integer NOT NULL,
 "defended" Date NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Add keys for table graduations

ALTER TABLE "graduations" ADD CONSTRAINT "PK_graduations" PRIMARY KEY ("id_certificates","id_attendances")
;

-- Table residences

CREATE TABLE "residences"(
 "id_residences" Serial NOT NULL,
 "id_postal_codes" Integer NOT NULL,
 "id_students" Integer NOT NULL,
 "address" Character varying(40) NOT NULL,
 "status" Character varying(7) NOT NULL
        CONSTRAINT "status_check" CHECK (status IN('STALNO', 'ZAČASNO'))
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table residences

CREATE INDEX "IX_residences_id_students" ON "residences" ("id_students")
;

CREATE INDEX "IX_residences_id_postal_codes" ON "residences" ("id_postal_codes")
;

-- Add keys for table residences

ALTER TABLE "residences" ADD CONSTRAINT "PK_residences" PRIMARY KEY ("id_residences")
;

-- Table partakings

CREATE TABLE "partakings"(
 "id_partakings" Serial NOT NULL,
 "id_scientific_papers" Integer,
 "id_attendances" Integer,
 "part" Character varying(30) NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table partakings

CREATE INDEX "IX_partakings_id_scientific_papers" ON "partakings" ("id_scientific_papers")
;

CREATE INDEX "IX_partakings_id_attendances" ON "partakings" ("id_attendances")
;

-- Add keys for table partakings

ALTER TABLE "partakings" ADD CONSTRAINT "PK_partakings" PRIMARY KEY ("id_partakings")
;

-- Table mentorings

CREATE TABLE "mentorings"(
 "id_mentorings" Serial NOT NULL,
 "id_faculties" Integer,
 "id_scientific_papers" Integer,
 "mentor" Character varying(60) NOT NULL,
 "taught" Character varying(40) NOT NULL,
 "email" Character varying(40) NOT NULL,
 "telephone" Character varying(13) NOT NULL
)
WITH (
 autovacuum_enabled=true)
;

-- Create indexes for table mentorings

CREATE INDEX "IX_mentorings_id_faculties" ON "mentorings" ("id_faculties")
;

CREATE INDEX "IX_mentorings_id_scientific_papers" ON "mentorings" ("id_scientific_papers")
;

-- Add keys for table mentorings

ALTER TABLE "mentorings" ADD CONSTRAINT "PK_mentorings" PRIMARY KEY ("id_mentorings")
;
-- Create foreign keys (relationships) section ------------------------------------------------- 

ALTER TABLE "attendances" ADD CONSTRAINT "students_attendances" FOREIGN KEY ("id_students") REFERENCES "students" ("id_students") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "attendances" ADD CONSTRAINT "faculties_attendances" FOREIGN KEY ("id_faculties") REFERENCES "faculties" ("id_faculties") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "graduations" ADD CONSTRAINT "certificates_graduations" FOREIGN KEY ("id_certificates") REFERENCES "certificates" ("id_certificates") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "attendances" ADD CONSTRAINT "programs_attendances" FOREIGN KEY ("id_programs") REFERENCES "programs" ("id_programs") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "documents" ADD CONSTRAINT "scientific_papers_documents" FOREIGN KEY ("id_scientific_papers") REFERENCES "scientific_papers" ("id_scientific_papers") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "residences" ADD CONSTRAINT "students_residences" FOREIGN KEY ("id_students") REFERENCES "students" ("id_students") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "residences" ADD CONSTRAINT "postal_codes_residences" FOREIGN KEY ("id_postal_codes") REFERENCES "postal_codes" ("id_postal_codes") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "faculties" ADD CONSTRAINT "postal_codes_faculties" FOREIGN KEY ("id_postal_codes") REFERENCES "postal_codes" ("id_postal_codes") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "postal_codes" ADD CONSTRAINT "countries_postal_codes" FOREIGN KEY ("id_countries") REFERENCES "countries" ("id_countries") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "partakings" ADD CONSTRAINT "scientific_papers_partakings" FOREIGN KEY ("id_scientific_papers") REFERENCES "scientific_papers" ("id_scientific_papers") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "mentorings" ADD CONSTRAINT "faculties_mentorings" FOREIGN KEY ("id_faculties") REFERENCES "faculties" ("id_faculties") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "mentorings" ADD CONSTRAINT "scientific_papers_mentorings" FOREIGN KEY ("id_scientific_papers") REFERENCES "scientific_papers" ("id_scientific_papers") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "graduations" ADD CONSTRAINT "attendances_graduations" FOREIGN KEY ("id_attendances") REFERENCES "attendances" ("id_attendances") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "programs" ADD CONSTRAINT "faculties_programs" FOREIGN KEY ("id_faculties") REFERENCES "faculties" ("id_faculties") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "scientific_papers" ADD CONSTRAINT "attendances_scientific_papers" FOREIGN KEY ("id_attendances") REFERENCES "attendances" ("id_attendances") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "partakings" ADD CONSTRAINT "attendances_partakings" FOREIGN KEY ("id_attendances") REFERENCES "attendances" ("id_attendances") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "students" ADD CONSTRAINT "postal_codes_students" FOREIGN KEY ("id_postal_codes") REFERENCES "postal_codes" ("id_postal_codes") ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE "accounts" ADD CONSTRAINT "attendances_accounts" FOREIGN KEY ("id_attendances") REFERENCES "attendances" ("id_attendances") ON DELETE NO ACTION ON UPDATE NO ACTION
;



-- Grant permissions section -------------------------------------------------

GRANT "authorized" TO "auth_69141238"
;


-- COUNTRIES
INSERT INTO
    countries 
(
    name, 
    iso_3_code
)
VALUES(
    'Albanija',
    'ALB'
),
(
    'Andora',
    'AND'
),
(
    'Avstrija',
    'AUT'
),
(
    'Belorusija',
    'BLR'
),
(
    'Belgija',
    'BEL'
),
(
    'Bosna in Hercegovina',
    'BIH'
),
(
    'Bolgarija',
    'BGR'
),
(
    'Hrvaška',
    'HRV'
),
(
    'Češka',
    'CZE'
),
(
    'Danska',
    'DNK'
),
(
    'Estonija',
    'EST'
),
(
    'Finska',
    'FIN'
),
(
    'Francija',
    'FRA'
),
(
    'Nemčija',
    'DEU'
),
(
    'Grčija',
    'GRC'
),
(
    'Vatikan',
    'VAT'
),
(
    'Madžarska',
    'HUN'
),
(
    'Islandija',
    'ISL'
),
(
    'Italija',
    'ITA'
),
(
    'Latvija',
    'LVA'
),
(
    'Lihtenštajn',
    'LIE'
),
(
    'Litva',
    'LTU'
),
(
    'Luksemburg',
    'LUX'
),
(
    'Makedonija',
    'MKD'
),
(
    'Malta',
    'MLT'
),
(
    'Moldavija',
    'MDA'
),
(
    'Monako',
    'MCO'
),
(
    'Črna Gora',
    'MNE'
),
(
    'Nizozemska',
    'NLD'
),
(
    'Norveška',
    'NOR'
),
(
    'Poljska',
    'POL'
),
(
    'Portugalska',
    'PRT'
),
(
    'Romunija',
    'ROU'
),
(
    'Rusija',
    'RUS'
),
(
    'Srbija',
    'SRB'
),
(
    'Slovaška',
    'SVK'
),
(
    'Slovenija',
    'SLO'
),
(
    'Španija',
    'ESP'
),
(
    'Švedska',
    'SWE'
),
(
    'Švica',
    'SUI'
),
(
    'Turčija',
    'TUR'
),
(
    'Ukrajina',
    'UKR'
),
(
    'Velika Britanija',
    'GBR'
);

-- POSTAL CODES
INSERT INTO 
    postal_codes 
(
    id_countries,
    municipality,
    code
)
VALUES(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Ajdovščina',
    '5270'
), 
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Artiče',
    '8253'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Bled',
    '4260'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Brežice',
    '8250'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Celje',
    '3000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Cerknica',
    '1380'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Črnomelj',
    '8340'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Čatež ob Savi',
    '8251'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Dravograd',
    '2370'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Domžale',
    '1230'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Franklovo',
    '3213'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Gabrovka',
    '1274'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Gradac',
    '8332'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Hrastnik',
    '1430'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Hoče',
    '2311'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Idrija',
    '5280'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Izola',
    '6310'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Jesenice',
    '4270'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Kranj',
    '4000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Koper',
    '6000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Krško',
    '8270'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Ljubljana',
    '1000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Laško',
    '3270'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Maribor',
    '2000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Murska Sobota',
    '9000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Nova Gorica',
    '5000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Novo Mesto',
    '8000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Ormož',
    '2270'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Piran',
    '6330'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Polzela',
    '3313'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Rogaška Slatina',
    '3250'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Rogatec',
    '3252'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Slovenj Gradec',
    '2380'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Slovenske Konjice',
    '3210'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Šentjur',
    '3230'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Škofja Loka',
    '4220'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Trbovlje',
    '1420'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Tolmin',
    '5220'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Velenje',
    '3320'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Vojnik',
    '3212'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Zidani Most',
    '1432'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Zreče',
    '3214'
),(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Žalec',
    '3310'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SLO'
    ),
    'Žabnica',
    '4209'
);

INSERT INTO 
    postal_codes 
(
    id_countries,
    municipality,
    code
)
VALUES(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Aleksandrovac',
    '37230'
), 
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Apatin',
    '25260'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Beograd',
    '11000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Bačka Palanka',
    '21400'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Valjevo',
    '14000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Vranje',
    '17501'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Gračanica',
    '38205'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Gajdobra',
    '21432'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Despotovac',
    '35213'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Dimitrovgrad',
    '18320'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Đurđevo',
    '21239'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Ečka',
    '23203'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Žitište',
    '23210'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Žiča',
    '36221'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Zaječar',
    '19000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Zlatibor',
    '31315'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Ivanjica',
    '32250'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Inđija',
    '22320'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Jagodina',
    '35000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Jelašnica',
    '17531'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Kraljevo',
    '36000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Kragujevac',
    '34000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Leskovac',
    '16000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Loznica',
    '15300'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Ljig',
    '14240'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Maglić',
    '21473'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Mokra Gora',
    '31243'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Niš',
    '18101'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Negotin',
    '19300'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Obrež',
    '22417'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Orašac',
    '34208'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Paraćin',
    '35250'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Pančevo',
    '26000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Raška',
    '36350'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Rudnik',
    '32313'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Senta',
    '24400'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Smederevo',
    '11300'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Takovo',
    '32304'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Temerin',
    '21235'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Ćuprija',
    '35230'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Užice',
    '31000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Futog',
    '21410'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Hajdučica',
    '26370'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Crvenka',
    '25220'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Čačak',
    '32000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Čenej',
    '21233'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Šabac',
    '15000'
),
(
    (
        SELECT 
            id_countries
        FROM 
            countries 
        WHERE 
            iso_3_code = 'SRB'
    ),
    'Šajkaš',
    '21244'
);

-- FACULTIES

INSERT INTO 
    faculties
(
    id_postal_codes,
    name,
    address,
    email,
    telephone,
    dean
)
VALUES(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Ekonomsko-poslovna fakulteta',
    'Razlagova 14',
    'epf@um.si',
    '+386 2 22 90 000',
    'Lidija Hauptman'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Fakulteta za elektrotehniko, računalništvo in informatiko',
    'Koroška cesta 46',
    'feri@um.si',
    '+386 2 220 7000',
    'Gorazd Štumberger'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '8270'
    ),
    'Fakulteta za energetiko',
    'Hočevarjev trg 1',
    'fe@um.si',
    '+386 7 6202 210',
    'Sebastijan Seme'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Fakulteta za gradbeništvo, prometno inženirstvo in arhitekturo',
    'Smetanova ulica 17',
    'fgpa@um.si',
    '+386 2 2294 300',
    'Vesna Žegarac Leskovar'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Fakulteta za kemijo in kemijsko tehnologijo',
    'Smetanova ulica 17',
    'fkkt@um.si',
    '+386 2 2294 400',
    'Zdravko Kravanja'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2311'
    ),
    'Fakulteta za kmetijstvo in biosistemske vede',
    'Pivola 10',
    'fkbv@um.si',
    '+386 2 320 90 00',
    'Branko Kramberger'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '3000'
    ),
    'Fakulteta za logistiko',
    'Mariborska cesta 7',
    ' info.fl@um.si',
    '+386 3 428 53 00',
    'Tomaž Kramberger'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Fakulteta za naravoslovje in matematiko',
    'Koroška cesta 160',
    'dekanat.fnm@um.si', 
    '+386 2 22 93 844',
    'Mitja Slavinec'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '4000'
    ),
    'Fakulteta za organizacijske vede',
    'Kidričeva cesta 55a',
    'dekanat.fov@um.si',
    '+386 4 23 74 200',
    'Polona Šprajc'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '4000'
    ),
    'Fakulteta za strojništvo',
    'Smetanova ulica 17',
    'fs@um.si',
    '+386 2 220 7500',
    'Bojan Dolšak'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '8250'
    ),
    'Fakulteta za turizem',
    'Cesta prvih borcev 36',
    'ft@um.si',
    '+386 8 20 54 013',
    'Boštjan Brumen'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '1000'
    ),
    'Fakulteta za varnostne vede',
    'Kotnikova ulica 8',
    'fvv@fvv.uni-mb.si',
    '+386 1 3008 300',
    'Andrej Sotlar'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Fakulteta za zdravstvene vede',
    'Žitna ulica 15',
    'fzv@um.si',
    '+386 2 300 47 00',
    'Klavdija Čuček Trifkovič'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Filozofska fakulteta',
    'Koroška cesta 160',
    'ff@um.si',
    '+386 2 229 3840',
    'Darko Friš'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Medicinska fakulteta',
    'Taborska ulica 8',
    'MF@Um.Si',
    '+386 02 2345 821',
    'Iztok Takač'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Pedagoška fakulteta',
    'Koroška cesta 160',
    'dekanat.pef@um.si',
    '+386 2 229 36 00',
    'Samo Fošnarič'
),
(
    (
        SELECT
            id_postal_codes
        FROM
            postal_codes
        WHERE
            code = '2000'
    ),
    'Pravna fakulteta',
    'Mladinska ulica 9',
    'info.pf@um.si',
    '+386 2 2504 200',
    'Vesna Rijavec'
);
    
-- PROGRAMS

INSERT INTO
    programs 
(
    id_faculties,
    name,
    degree,
    duration,
    field
)
VALUES(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Ekonomske in poslovne vede',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Ekonomske in poslovne vede',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Ekonomske in poslovne vede',
    'VIII/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Gospodarsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Gospodarsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Gospodarsko inženirstvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%EKONOMSKO-POSLOVNA FAKULTETA%'
    ),
    'Poslovna ekonomija',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Elektrotehnika',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Elektrotehnika',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Elektrotehnika',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Gospodarsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Gospodarsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Informatika in tehnologije komuniciranja',
    'VI/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Informatika in tehnologije komuniciranja',
    'VII',
    '2',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Medijske komunikacije',
    'VI/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Medijske komunikacije',
    'VII',
    '2',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Medijske komunikacije',
    'VIII/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Mehatronika',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Mehatronika',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Računalništvo in informacijske tehnologije',
    'VI/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Računalništvo in informacijske tehnologije',
    'VII',
    '2',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Računalništvo in informatika',
    'VIII/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Telekomunikacije',
    'VI/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ELEKTROTEHNIKO, RAČUNALNIŠTVO IN INFORMATIKO%'
    ),
    'Telekomunikacije',
    'VII',
    '2',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ENERGETIKO%'
    ),
    'Energetika',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ENERGETIKO%'
    ),
    'Energetika',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ENERGETIKO%'
    ),
    'Energetika',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Arhitektura',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Arhitektura',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gospodarsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gospodarsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gospodarsko inženirstvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gradbeništvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gradbeništvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Gradbeništvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Prometno inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Prometno inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA GRADBENIŠTVO, PROMETNO INŽENIRSTVO IN ARHITEKTURO%'
    ),
    'Prometno inženirstvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemija',
    'VI/2',
    '3',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemija',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemija in kemijsko inženirstvo',
    'VIII/2',
    '4',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemijska tehnologija',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemijsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KEMIJO IN KEMIJSKO TEHNOLOGIJO%'
    ),
    'Kemijsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Agrarna ekonomika',
    'VII',
    '2',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Agrarna ekonomika',
    'VIII/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Agrarna ekonomika in razvoj podeželja',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Agrikultura in okolje',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Agronomija - okrasne rastilne, zelenjava in poljščine',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Biosistemsko inženirstvo',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Ekološko kmetijstvo',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Kmetijstvo',
    'VII',
    '2',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Kmetijstvo',
    'VIII/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Varnost hrane v prehrambeni verigi',
    'VII',
    '2',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Vinogradništvo, vinarstvo in sadjarstvo',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA KMETIJSTVO IN BIOSISTEMSKE VEDE%'
    ),
    'Živinoreja',
    'VI/2',
    '3',
    'Kmetijstvo, gozdrastvo, ribištvo in veterinarstvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA LOGISTIKO%'
    ),
    'Gospodarska in tehniška logistika',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA LOGISTIKO%'
    ),
    'Logistika sistemov',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA LOGISTIKO%'
    ),
    'Logistika sistemov',
    'VII',
    '2',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA LOGISTIKO%'
    ),
    'Logistika sistemov',
    'VIII/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Biologija',
    'VI/2',
    '3',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Biologija in ekologija z naravovarstvom',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Ekologija z naravovarstvom',
    'VI/2',
    '3',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Ekološke znanosti',
    'VIII/2',
    '4',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Fizika',
    'VI/2',
    '3',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Fizika',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Fizika',
    'VIII/2',
    '4',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Izobraževalna matematika',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Izobraževalna tehnika',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Matematika',
    'VI/2',
    '3',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Matematika',
    'VII',
    '2',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Matematika',
    'VIII/2',
    '4',
    'Naravoslovje, matematika in statistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Predmetni učitelj',
    'VII',
    '5',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA NARAVOSLOVJE IN MATEMATIKO%'
    ),
    'Tehnika - področje izobraževanja',
    'VIII/2',
    '4',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Inženiring poslovnih sistemov',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Inženiring poslovnih sistemov',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Krizni management',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Management v športu',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Management v zdravstu in socialnem varstvu',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management informacijskih sistemov',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management informacijskih sistemov',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management informacijskih sistemov',
    'VIII/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management kadrovskih in izobraževalnih sistemov',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management kadrovskih in izobraževalnih sistemov',
    'VIII/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ORGANIZACIJSKE VEDE%'
    ),
    'Organizacija in management poslovnih in delovnih sistemov',
    'VIII/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Doktorska šola fakultete za strojništvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Gospodarsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Gospodarsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Gospodarsko inženirstvo',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Inženirsko oblikovanje izdelkov',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Mehatronika',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Mehatronika',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Oblikovanje in tekstilni materiali',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Oblikovanje in tekstilni materiali',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Oblikovanje in tekstilni materiali',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Okoljsko inženirstvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Okoljsko inženirstvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Strojništvo',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Strojništvo',
    'VII',
    '2',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Tehniško varstvo okolja',
    'VIII/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA STROJNIŠTVO%'
    ),
    'Tehnologije tekstilnega oblikovanja',
    'VI/2',
    '3',
    'Tehnika, proizvodne tehnologije in gradbeništvo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA TURIZEM%'
    ),
    'Magistrski študijski program turistične destinacije in doživetja',
    'VII',
    '2',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA TURIZEM%'
    ),
    'Univerzitetni študijski program turizem: sodelovanje in razvoj',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA TURIZEM%'
    ),
    'Visokošolski storkovni študijski program sodobne turistične prakse',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA VARNOSTNE VEDE%'
    ),
    'Informacijska varnost',
    'VI/2',
    '3',
    'Informacijske in komunikacijske tehnologije(IKT)'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA VARNOSTNE VEDE%'
    ),
    'Varnost in policijsko delo',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA VARNOSTNE VEDE%'
    ),
    'Varstvoslovje',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA VARNOSTNE VEDE%'
    ),
    'Varstvoslovje',
    'VII',
    '2',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA VARNOSTNE VEDE%'
    ),
    'Varstvoslovje',
    'VIII/2',
    '4',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ZDRAVSTVENE VEDE%'
    ),
    'Bioinformatika',
    'VII',
    '2',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ZDRAVSTVENE VEDE%'
    ),
    'Management v zdravstu in socialnem varstvu',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ZDRAVSTVENE VEDE%'
    ),
    'Zdravstvena nega',
    'VI/2',
    '3',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ZDRAVSTVENE VEDE%'
    ),
    'Zdravstvena nega',
    'VII',
    '2',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FAKULTETA ZA ZDRAVSTVENE VEDE%'
    ),
    'Zdravstvena nega',
    'VIII/2',
    '3',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Angleški jezik in književnost',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Anglistika',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Filozofija',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Filozofija',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Filozofija',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Filozofija',
    'VIII/2',
    '4',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Geografija',
    'VI/2',
    '3',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Geografija',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Geografija',
    'VII',
    '2',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Geografija',
    'VIII/2',
    '4',
    'Družbene vede, novinarstvo in informacijska znanost'
),(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Germanistične študije',
    'VIII/2',
    '4',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Germanistika',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Madžarski jezik s književnostjo',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Madžarski jezik s književnostjo',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Medjezikovne študije - madžarščina',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Medkulturna germanistika',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Nemščina kot tuji jezik',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Nemški jezik in književnost',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Pedagogika',
    'VI/2',
    '3',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Pedagogika',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Pedagogika',
    'VIII/2',
    '4',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Poučevanje Angleščine',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Prevajalske študije - Angleščina',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Prevajalske študije - Nemščina',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Prevajanje in tolmačenje',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Psihologija',
    'VI/2',
    '3',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Psihologija',
    'VII',
    '2',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Psihologija',
    'VIII/2',
    '4',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Slovenistične študije',
    'VIII/2',
    '4',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Slovenski jezik in književnost',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Slovenski jezik in književnost',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Slovenski jezik in književnost',
    'VI/2',
    '2',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Sociologija',
    'VI/2',
    '3',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Sociologija',
    'VII',
    '2',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Sociologija',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Sociologija',
    'VIII/2',
    '4',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Sociologija in interdisciplinarno družboslovje',
    'VI/2',
    '3',
    'Družbene vede, novinarstvo in informacijska znanost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Umetnostna zgodovina',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Umetnostna zgodovina',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Umetnostna zgodovina',
    'VIII/2',
    '4',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Zgodovina',
    'VI/2',
    '3',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Zgodovina',
    'VII',
    '2',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Zgodovina',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%FILOZOFSKA FAKULTETA%'
    ),
    'Zgodovina',
    'VIII/2',
    '4',
    'Umetnost in humanistika'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%MEDICINSKA FAKULTETA%'
    ),
    'Biomedicinska tehnologija',
    'VIII/2',
    '3',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%MEDICINSKA FAKULTETA%'
    ),
    'Dentalna medicina',
    'VII',
    '6',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%MEDICINSKA FAKULTETA%'
    ),
    'Splošna medicina',
    'VII',
    '6',
    'Zdravstvo in socialna varnost'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Edukacijske vede',
    'VIII/2',
    '3',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Glasbena pedagogika',
    'VI/2',
    '4',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Glasbena pedagogika',
    'VII',
    '1',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Inkluzija v vzgoji in izobraževanju',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Likovna pedagogika',
    'VI/2',
    '4',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Likovna pedagogika',
    'VII',
    '1',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Predšolska vzgoja',
    'VI/2',
    '3',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Predšolska vzgoja',
    'VII',
    '2',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Razredni pouk',
    'VI/2',
    '4',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Razredni pouk',
    'VII',
    '1',
    'Izobraževalne znanosti in izobraževanje učiteljev'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PEDAGOŠKA FAKULTETA%'
    ),
    'Športno treniranje',
    'VI/2',
    '3',
    'Transport, varnost, gostinstvo in turizem, osebne storitve'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PRAVNA FAKULTETA%'
    ),
    'Evropske pravne študije',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PRAVNA FAKULTETA%'
    ),
    'Pravo',
    'VI/2',
    '3',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PRAVNA FAKULTETA%'
    ),
    'Pravo',
    'VII',
    '2',
    'Poslovne in upravne vede, pravo'
),
(
    (
        SELECT    
            id_faculties
        FROM    
            faculties
        WHERE
            UPPER(name) LIKE '%PRAVNA FAKULTETA%'
    ),
    'Pravo',
    'VIII/2',
    '3',
    'Poslovne in upravne vede, pravo'
);
