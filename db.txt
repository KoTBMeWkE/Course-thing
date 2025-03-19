--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO postgres;

--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: contests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contests (
    id_contest integer NOT NULL,
    title character varying NOT NULL,
    date date NOT NULL,
    description text NOT NULL,
    subject character varying NOT NULL,
    winning_student integer,
    teacher_id integer NOT NULL
);


ALTER TABLE public.contests OWNER TO postgres;

--
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.groups (
    id_group integer NOT NULL,
    study_year_start integer NOT NULL,
    students_amount integer NOT NULL,
    group_name character varying NOT NULL
);


ALTER TABLE public.groups OWNER TO postgres;

--
-- Name: student_contests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.student_contests (
    student_id integer NOT NULL,
    contest_id integer NOT NULL
);


ALTER TABLE public.student_contests OWNER TO postgres;

--
-- Name: students; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.students (
    id_student integer NOT NULL,
    name character varying NOT NULL,
    surname character varying NOT NULL,
    lastname character varying NOT NULL,
    group_id integer NOT NULL,
    birthdate date NOT NULL,
    live_place character varying,
    parent_contact character varying,
    CONSTRAINT students_id_student_check CHECK (((id_student > 10000) AND (id_student <= 10000000)))
);


ALTER TABLE public.students OWNER TO postgres;

--
-- Name: teachers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.teachers (
    id_prep integer NOT NULL,
    name character varying NOT NULL,
    surname character varying NOT NULL,
    lastname character varying NOT NULL,
    group_id integer,
    phone_num character varying NOT NULL,
    email character varying NOT NULL,
    CONSTRAINT teachers_id_prep_check CHECK (((id_prep >= 1) AND (id_prep <= 10000)))
);


ALTER TABLE public.teachers OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id_user integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(20) NOT NULL,
    student_id integer,
    teacher_id integer,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY (ARRAY[('admin'::character varying)::text, ('teacher'::character varying)::text, ('student'::character varying)::text])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Data for Name: contests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contests (id_contest, title, date, description, subject, winning_student, teacher_id) FROM stdin;
1	Конкурс рисунков	2025-11-04	Конкурс для детей на лучшее художественное произведение.	Искусство	\N	1
2	Творческий фестиваль	2025-10-15	Фестиваль детского творчества с различными номинациями.	Искусство	10005	2
3	Конкурс поэзии	2025-09-20	Конкурс стихотворений для юных поэтов.	Литература	\N	3
4	Конкурс танцев	2025-08-30	Соревнование для детей в различных танцевальных стилях.	Танцы	10012	4
5	Конкурс театральных постановок	2025-07-25	Конкурс для школьных театров и театральных коллективов.	Театр	\N	5
6	Конкурс музыкальных исполнений	2025-06-10	Конкурс для юных музыкантов и вокалистов.	Музыка	10020	1
7	Выставка детского творчества	2025-05-15	Выставка работ детей в различных жанрах.	Искусство	\N	2
8	Конкурс поделок	2025-04-20	Конкурс на лучшее изделие из подручных материалов.	Творчество	10015	3
9	Конкурс сказок	2025-03-30	Конкурс на лучшее сказочное произведение.	Литература	\N	4
10	Конкурс фотографий	2025-02-28	Конкурс на лучшее фото, отражающее детское творчество.	Фотография	10025	5
11	Конкурс кулинарного искусства	2025-01-15	Конкурс для юных поваров и кулинаров.	Кулинария	\N	1
12	Конкурс оригами	2025-12-10	Конкурс на лучшее изделие в технике оригами.	Творчество	10030	2
13	Конкурс литературных героев	2025-11-20	Конкурс на лучшее изображение литературного героя.	Литература	\N	3
14	Конкурс видеороликов	2025-10-05	Конкурс на лучшее видео, созданное детьми.	Мультимедиа	10035	4
15	Конкурс экологических проектов	2025-09-15	Конкурс на лучшее экологическое решение или проект.	Экология	\N	5
\.


--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.groups (id_group, study_year_start, students_amount, group_name) FROM stdin;
1	2023	11	Творческая мастерская
2	2024	13	Клуб юных художников
3	2023	9	Театральная студия
4	2025	13	Музыкальный коллектив
5	2023	14	Литературный кружок
6	2023	11	Кулинарная школа
7	2025	12	Группа оригами
8	2024	15	Клуб юных исследователей
9	2023	14	Студия танцев
10	2024	8	Экологический отряд
\.


--
-- Data for Name: student_contests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.student_contests (student_id, contest_id) FROM stdin;
\.


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.students (id_student, name, surname, lastname, group_id, birthdate, live_place, parent_contact) FROM stdin;
10001	Анна	Сергеевна	Смирнова	6	2008-05-14	ул. Ленина, д. 1	+7 (999) 111-11-11
10002	Максим	Александрович	Иванов	4	2007-10-23	ул. Пушкина, д. 2	+7 (999) 222-22-22
10003	Екатерина	Дмитриевна	Петрова	5	2008-08-03	ул. Сибирская, д. 3	+7 (999) 333-33-33
10004	Дмитрий	Викторович	Сидоров	3	2008-01-21	ул. Мира, д. 4	+7 (999) 444-44-44
10005	Ольга	Николаевна	Кузнецова	9	2007-12-19	ул. Ленина, д. 5	+7 (999) 555-55-55
10006	Алексей	Павлович	Фёдоров	6	2008-03-25	ул. Гагарина, д. 6	+7 (999) 666-66-66
10007	Мария	Андреевна	Васильева	8	2008-02-02	ул. Чапаева, д. 7	+7 (999) 777-77-77
10008	Николай	Игоревич	Орлов	7	2008-11-05	ул. Куйбышева, д. 8	+7 (999) 888-88-88
10009	София	Евгеньевна	Михайлова	1	2008-02-17	ул. Садовая, д. 9	+7 (999) 999-99-99
10010	Артём	Валерьевич	Лебедев	8	2007-08-19	ул. Ленина, д. 10	+7 (999) 000-00-00
10011	Ирина	Станиславовна	Ковалёва	4	2008-04-15	ул. Пушкина, д. 11	+7 (999) 111-12-13
10012	Сергей	Анатольевич	Соловьёв	2	2008-06-30	ул. Мира, д. 12	+7 (999) 222-23-24
10013	Елена	Викторовна	Григорьева	5	2008-07-22	ул. Гагарина, д. 13	+7 (999) 333-34-35
10014	Андрей	Сергеевич	Фролов	6	2008-09-10	ул. Чапаева, д. 14	+7 (999) 444-45-46
10015	Татьяна	Александровна	Семенова	9	2008-10-05	ул. Садовая, д. 15	+7 (999) 555-56-57
10016	Виктор	Петрович	Кузнецов	1	2008-11-20	ул. Ленина, д. 16	+7 (999) 666-67-68
10017	Наталья	Ивановна	Сидорова	5	2008-12-15	ул. Пушкина, д. 17	+7 (999) 777-78-79
10018	Денис	Александрович	Морозов	3	2008-01-30	ул. Мира, д. 18	+7 (999) 888-89-90
10019	Ксения	Дмитриевна	Лебедева	7	2008-02-25	ул. Гагарина, д. 19	+7 (999) 999-00-01
10020	Роман	Станиславович	Ковалёв	4	2008-03-10	ул. Чапаева, д. 20	+7 (999) 111-01-02
10021	Анастасия	Викторовна	Григорьева	1	2008-04-05	ул. Садовая, д. 21	+7 (999) 222-02-03
10022	Станислав	Анатольевич	Фролов	4	2008-05-15	ул. Ленина, д. 22	+7 (999) 333-03-04
10023	Евгения	Сергеевна	Семенова	1	2008-06-20	ул. Пушкина, д. 23	+7 (999) 444-04-05
10024	Игорь	Петрович	Кузнецов	5	2008-07-25	ул. Мира, д. 24	+7 (999) 555-05-06
10025	Оксана	Ивановна	Сидорова	1	2008-08-30	ул. Гагарина, д. 25	+7 (999) 666-06-07
10026	Александр	Александрович	Морозов	6	2008-09-15	ул. Чапаева, д. 26	+7 (999) 777-07-08
10027	Марина	Дмитриевна	Лебедева	2	2008-10-10	ул. Садовая, д. 27	+7 (999) 888-08-09
10028	Владимир	Станиславович	Ковалёв	9	2008-11-05	ул. Ленина, д. 28	+7 (999) 999-09-10
10029	Татьяна	Викторовна	Григорьева	9	2008-12-20	ул. Пушкина, д. 29	+7 (999) 111-10-11
10030	Сергей	Анатольевич	Фролов	1	2008-01-25	ул. Мира, д. 30	+7 (999) 222-11-12
10031	Елена	Сергеевна	Семенова	2	2008-02-15	ул. Гагарина, д.  31	+7 (999) 333-12-13
10032	Андрей	Петрович	Кузнецов	7	2008-03-20	ул. Чапаева, д. 32	+7 (999) 444-13-14
10033	Наталья	Александровна	Сидорова	6	2008-04-10	ул. Садовая, д. 33	+7 (999) 555-14-15
10034	Денис	Станиславович	Морозов	5	2008-05-05	ул. Ленина, д. 34	+7 (999) 666-15-16
10035	Ксения	Дмитриевна	Лебедева	4	2008-06-15	ул. Пушкина, д. 35	+7 (999) 777-16-17
10036	Роман	Анатольевич	Ковалёв	3	2008-07-20	ул. Мира, д. 36	+7 (999) 888-17-18
10037	Анастасия	Сергеевна	Григорьева	9	2008-08-25	ул. Гагарина, д. 37	+7 (999) 999-18-19
10038	Станислав	Викторович	Фролов	6	2008-09-30	ул. Чапаева, д. 38	+7 (999) 111-19-20
10039	Евгения	Александровна	Семенова	10	2008-10-15	ул. Садовая, д. 39	+7 (999) 222-20-21
10040	Игорь	Петрович	Кузнецов	8	2008-11-10	ул. Ленина, д. 40	+7 (999) 333-21-22
10041	Оксана	Ивановна	Сидорова	9	2008-12-05	ул. Пушкина, д. 41	+7 (999) 444-22-23
10042	Александр	Александрович	Морозов	9	2008-01-15	ул. Мира, д. 42	+7 (999) 555-23-24
10043	Марина	Дмитриевна	Лебедева	2	2008-02-10	ул. Гагарина, д. 43	+7 (999) 666-24-25
10044	Владимир	Станиславович	Ковалёв	8	2008-03-05	ул. Чапаева, д. 44	+7 (999) 777-25-26
10045	Татьяна	Викторовна	Григорьева	4	2008-04-20	ул. Садовая, д. 45	+7 (999) 888-26-27
10046	Сергей	Анатольевич	Фролов	5	2008-05-25	ул. Ленина, д. 46	+7 (999) 999-27-28
10047	Елена	Сергеевна	Семенова	5	2008-06-30	ул. Пушкина, д. 47	+7 (999) 111-28-29
10048	Андрей	Петрович	Кузнецов	7	2008-07-15	ул. Мира, д. 48	+7 (999) 222-29-30
10049	Наталья	Александровна	Сидорова	1	2008-08-20	ул. Гагарина, д. 49	+7 (999) 333-30-31
10050	Денис	Станиславович	Морозов	4	2008-09-25	ул. Чапаева, д. 50	+7 (999) 444-31-32
10051	Ксения	Дмитриевна	Лебедева	9	2008-10-30	ул. Садовая, д. 51	+7 (999) 555-32-33
10052	Роман	Анатольевич	Ковалёв	9	2008-11-15	ул. Ленина, д. 52	+7 (999) 666-33-34
10053	Анастасия	Сергеевна	Григорьева	6	2008-12-10	ул. Пушкина, д. 53	+7 (999) 777-34-35
10054	Станислав	Викторович	Фролов	5	2008-01-05	ул. Мира, д. 54	+7 (999) 888-35-36
10055	Евгения	Александровна	Семенова	10	2008-02-20	ул. Гагарина, д. 55	+7 (999) 999-36-37
10056	Игорь	Петрович	Кузнецов	8	2008-03-15	ул. Чапаева, д. 56	+7 (999) 111-37-38
10057	Оксана	Ивановна	Сидорова	4	2008-04-10	ул. Садовая, д. 57	+7 (999) 222-38-39
10058	Александр	Александрович	Морозов	3	2008-05-05	ул. Ленина, д. 58	+7 (999) 333-39-40
10059	Марина	Дмитриевна	Лебедева	2	2008-06-30	ул. Пушкина, д. 59	+7 (999) 444-40-41
10060	Владимир	Станиславович	Ковалёв	7	2008-07-25	ул. Мира, д. 60	+7 (999) 555-41-42
10061	Татьяна	Викторовна	Григорьева	2	2008-08-20	ул. Гагарина, д. 61	+7 (999) 666-42-43
10062	Сергей	Анатольевич	Фролов	6	2008-09-15	ул. Чапаева, д. 62	+7 (999) 777-43-44
10063	Елена	Сергеевна	Семенова	9	2008-10-10	ул. Садовая, д. 63	+7 (999) 888-44-45
10064	Андрей	Петрович	Кузнецов	8	2008-11-05	ул. Ленина, д. 64	+7 (999) 999-45-46
10065	Наталья	Александровна	Сидорова	5	2008-12-01	ул. Пушкина, д. 65	+7 (999) 111-46-47
10066	Денис	Станиславович	Морозов	3	2008-01-20	ул. Мира, д. 66	+7 (999) 222-47-48
10067	Ксения	Дмитриевна	Лебедева	4	2008-02-15	ул. Гагарина, д. 67	+7 (999) 333-48-49
10068	Роман	Анатольевич	Ковалёв	7	2008-03-10	ул. Чапаева, д. 68	+7 (999) 444-49-50
10069	Анастасия	Сергеевна	Григорьева	9	2008-04-05	ул. Садовая, д. 69	+7 (999) 555-50-51
10070	Станислав	Викторович	Фролов	2	2008-05-30	ул. Ленина, д. 70	+7 (999) 666-51-52
10071	Евгения	Александровна	Семенова	5	2008-06-25	ул. Пушкина, д. 71	+7 (999) 777-52-53
10072	Игорь	Петрович	Кузнецов	6	2008-07-20	ул. Мира, д. 72	+7 (999) 888-53-54
10073	Оксана	Ивановна	Сидорова	8	2008-08-15	 ул. Гагарина, д. 73	+7 (999) 999-54-55
10074	Александр	Александрович	Морозов	9	2008-09-10	ул. Чапаева, д. 74	+7 (999) 111-55-56
10075	Марина	Дмитриевна	Лебедева	4	2008-10-05	ул. Садовая, д. 75	+7 (999) 222-56-57
10076	Владимир	Станиславович	Ковалёв	8	2008-11-01	ул. Ленина, д. 76	+7 (999) 333-57-58
10077	Татьяна	Викторовна	Григорьева	5	2008-12-15	ул. Пушкина, д. 77	+7 (999) 444-58-59
10078	Сергей	Анатольевич	Фролов	9	2008-01-10	ул. Мира, д. 78	+7 (999) 555-59-60
10079	Елена	Сергеевна	Семенова	7	2008-02-05	ул. Гагарина, д. 79	+7 (999) 666-60-61
10080	Андрей	Петрович	Кузнецов	6	2008-03-01	ул. Чапаева, д. 80	+7 (999) 777-61-62
10081	Наталья	Александровна	Сидорова	2	2008-04-25	ул. Садовая, д. 81	+7 (999) 888-62-63
10082	Денис	Станиславович	Морозов	8	2008-05-20	ул. Ленина, д. 82	+7 (999) 999-63-64
10083	Ксения	Дмитриевна	Лебедева	4	2008-06-15	ул. Пушкина, д. 83	+7 (999) 111-64-65
10084	Роман	Анатольевич	Ковалёв	1	2008-07-10	ул. Мира, д. 84	+7 (999) 222-65-66
10085	Анастасия	Сергеевна	Григорьева	2	2008-08-05	ул. Гагарина, д. 85	+7 (999) 333-66-67
10086	Станислав	Викторович	Фролов	3	2008-09-01	ул. Чапаева, д. 86	+7 (999) 444-67-68
10087	Евгения	Александровна	Семенова	5	2008-09-30	ул. Садовая, д. 87	+7 (999) 555-68-69
10088	Игорь	Петрович	Кузнецов	2	2008-10-25	ул. Ленина, д. 88	+7 (999) 666-69-70
10089	Оксана	Ивановна	Сидорова	8	2008-11-20	ул. Пушкина, д. 89	+7 (999) 777-70-71
10090	Александр	Александрович	Морозов	10	2008-12-15	ул. Мира, д. 90	+7 (999) 888-71-72
10091	Марина	Дмитриевна	Лебедева	9	2008-01-10	ул. Гагарина, д. 91	+7 (999) 999-72-73
10092	Владимир	Станиславович	Ковалёв	8	2008-02-05	ул. Чапаева, д. 92	+7 (999) 111-73-74
10093	Татьяна	Викторовна	Григорьева	4	2008-03-01	ул. Садовая, д. 93	+7 (999) 222-74-75
10094	Сергей	Анатольевич	Фролов	1	2008-03-30	ул. Ленина, д. 94	+7 (999) 333-75-76
10095	Елена	Сергеевна	Семенова	6	2008-04-25	ул. Пушкина, д. 95	+7 (999) 444-76-77
10096	Андрей	Петрович	Кузнецов	7	2008-05-20	ул. Мира, д. 96	+7 (999) 555-77-78
10097	Наталья	Александровна	Сидорова	1	2008-06-15	ул. Гагарина, д. 97	+7 (999) 666-78-79
10098	Денис	Станиславович	Морозов	7	2008-07-10	ул. Чапаева, д. 98	+7 (999) 777-79-80
10099	Ксения	Дмитриевна	Лебедева	8	2008-08-05	ул. Садовая, д. 99	+7 (999) 888-80-81
10100	Роман	Анатольевич	Ковалёв	2	2008-09-01	ул. Ленина, д. 100	+7 (999) 999-81-82
10101	Анастасия	Сергеевна	Григорьева	8	2008-09-30	ул. Пушкина, д. 101	+7 (999) 111-82-83
10102	Станислав	Викторович	Фролов	8	2008-10-25	ул. Мира, д. 102	+7 (999) 222-83-84
10103	Евгения	Александровна	Семенова	10	2008-11-20	ул. Гагарина, д. 103	+7 (999) 333-84-85
10104	Игорь	Петрович	Кузнецов	1	2008-12-15	ул. Чапаева, д. 104	+7 (999) 444-85-86
10105	Оксана	Ивановна	Сидорова	10	2008-01-10	ул. Садовая, д. 105	+7 (999) 555-86-87
10106	Александр	Александрович	Морозов	1	2008-02-05	ул. Ленина, д. 106	+7 (999) 666-87-88
10107	Марина	Дмитриевна	Лебедева	4	2008-03-01	ул. Пушкина, д. 107	+7 (999) 777-88-89
10108	Владимир	Станиславович	Ковалёв	7	2008-03-30	ул. Чапаева, д. 108	+7 (999) 888-89-90
10109	Татьяна	Викторовна	Григорьева	1	2008-04-25	ул. Садовая, д. 109	+7 (999) 999-90-91
10110	Сергей	Анатольевич	Фролов	1	2008-05-20	ул. Ленина, д. 110	+7 (999) 111-91-92
10111	Елена	Сергеевна	Семенова	8	2008-06-15	ул. Пушкина, д. 111	+7 (999) 222-92-93
10112	Андрей	Петрович	Кузнецов	1	2008-07-10	ул. Мира, д. 112	+7 (999) 333-93-94
10113	Наталья	Александровна	Сидорова	10	2008-08-05	ул. Гагарина, д. 113	+7 (999) 444-94 - 95
10114	Денис	Станиславович	Морозов	7	2008-09-01	ул. Чапаева, д. 114	+7 (999) 555-95-96
10115	Ксения	Дмитриевна	Лебедева	2	2008-09-30	ул. Садовая, д. 115	+7 (999) 666-96-97
10116	Роман	Анатольевич	Ковалёв	5	2008-10-25	ул. Ленина, д. 116	+7 (999) 777-97-98
10117	Анастасия	Сергеевна	Григорьева	10	2008-11-20	ул. Пушкина, д. 117	+7 (999) 888-98-99
10118	Станислав	Викторович	Фролов	7	2008-12-15	ул. Мира, д. 118	+7 (999) 999-99-00
10119	Евгения	Александровна	Семенова	5	2008-01-10	Гагарина, д. 119	+7 (999) 111-00-01
10120	Игорь	Петрович	Кузнецов	2	2008-02-05	ул. Чапаева, д. 120	+7 (999) 222-01-02
\.


--
-- Data for Name: teachers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.teachers (id_prep, name, surname, lastname, group_id, phone_num, email) FROM stdin;
1	Елена	Викторовна	Смирнова	1	+7 (999) 111-11-11	elena.smirnova@example.com
2	Александр	Иванович	Петров	2	+7 (999) 222-22-22	alexander.petrov@example.com
3	Мария	Сергеевна	Кузнецова	\N	+7 (999) 333-33-33	maria.kuznetsova@example.com
4	Дмитрий	Анатольевич	Сидоров	3	+7 (999) 444-44-44	dmitry.sidorov@example.com
5	Ольга	Николаевна	Фёдорова	4	+7 (999) 555-55-55	olga.fyodorova@example.com
6	Сергей	Валерьевич	Морозов	\N	+7 (999) 666-66-66	sergey.morozov@example.com
7	Анна	Станиславовна	Григорьева	5	+7 (999) 777-77-77	anna.grigorieva@example.com
8	Игорь	Петрович	Лебедев	\N	+7 (999) 888-88-88	igor.lebedev@example.com
9	Татьяна	Александровна	Семенова	\N	+7 (999) 999-99-99	tatiana.semenova@example.com
10	Виктор	Сергеевич	Ковалёв	6	+7 (999) 000-00-00	victor.kovalev@example.com
11	Ксения	Дмитриевна	Сидорова	\N	+7 (999) 111-12-13	kseniya.sidorova@example.com
12	Роман	Анатольевич	Фролов	7	+7 (999) 222-23-24	roman.frolov@example.com
13	Евгения	Станиславовна	Кузнецова	8	+7 (999) 333-34-35	evgeniya.kuznetsova@example.com
14	Андрей	Игоревич	Смирнов	9	+7 (999) 444-45-46	andrey.smирнов@example.com
15	Наталья	Викторовна	Петрова	10	+7 (999) 555-56-57	natalya.petrova@example.com
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id_user, username, password, role, student_id, teacher_id) FROM stdin;
0	admin	$2a$06$.BgssJJrEDOgdoSG2VlTS.FXhCmtJaSDrVnYfDqcihIqKKwOmen56	admin	\N	\N
1	artistic_student	$2a$06$gDj3IyLibGVHyBnTM5gXEOyfqhBM7vvJ8Ix3mkdSnaTmVtLiLMupe	student	10001	\N
2	math_whiz	$2a$06$a/iRRQRJePL9YDiziqZTbuwiRrRRbMH7WuTchhA8P87LpkS0nSyri	student	10002	\N
3	future_leader	$2a$06$Xued2CtRSORYEThYO73oq.0/TNjF31N926Ma8frePsJHwQ9VmcYym	student	10003	\N
4	creative_teacher	$2a$06$4X3CbcDgs3L40a1xWzHisO/DLDLAUk8nPW1142FsRQX5J4NO/Z5n2	teacher	\N	1
5	history_expert	$2a$06$Lc/0Kbfjxs0/Ml9EtT5Sq.XpTmJhHjn/QTNzEAkMLnhtq8zyhtZfm	teacher	\N	2
6	science_guru	$2a$06$F3NRkR8KnUV5lRntInC2eOW4jtnWknY6F2cGNxb2ill1ZpNbo1izy	teacher	\N	3
7	young_inventor	$2a$06$uxHqBIb6vU09nnuxVI4yv.cmHFLrHNgNaZWQeAuaXHaQZYDZx/el6	student	10004	\N
8	literature_lover	$2a$06$kMVdlYeK2wXo785rSd8uwOK.Efc2fTfbko6gg3L7vdKQ/Wgh.lq9a	student	10005	\N
9	music_mentor	$2a$06$sYXpwWg5OyEZjTOvthFJ7edkcE2n2pSbHq5tzWFBzkrt5d79NgNnq	teacher	\N	4
10	art_instructor	$2a$06$uMQGxMhCj1aw4SNI8jfz6OtLFK6qV5huFNsFrEnY6OWsBXlRs6hzm	teacher	\N	5
\.


--
-- Name: contests contests_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_pk PRIMARY KEY (id_contest);


--
-- Name: groups groups_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pk PRIMARY KEY (id_group);


--
-- Name: student_contests student_contests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_pkey PRIMARY KEY (student_id, contest_id);


--
-- Name: students students_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pk PRIMARY KEY (id_student);


--
-- Name: teachers teachers_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pk PRIMARY KEY (id_prep);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);


--
-- Name: contests contests_students_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_students_fk FOREIGN KEY (winning_student) REFERENCES public.students(id_student);


--
-- Name: contests contests_teachers_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_teachers_fk FOREIGN KEY (teacher_id) REFERENCES public.teachers(id_prep);


--
-- Name: users fk_students; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_students FOREIGN KEY (student_id) REFERENCES public.students(id_student);


--
-- Name: users fk_teachers; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_teachers FOREIGN KEY (teacher_id) REFERENCES public.teachers(id_prep);


--
-- Name: student_contests student_contests_contest_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_contest_id_fkey FOREIGN KEY (contest_id) REFERENCES public.contests(id_contest);


--
-- Name: student_contests student_contests_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_student_id_fkey FOREIGN KEY (student_id) REFERENCES public.students(id_student);


--
-- Name: students students_groups_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_groups_fk FOREIGN KEY (group_id) REFERENCES public.groups(id_group);


--
-- Name: teachers teachers_groups_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_groups_fk FOREIGN KEY (group_id) REFERENCES public.groups(id_group);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

