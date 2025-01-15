--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2 (Debian 17.2-1.pgdg120+1)
-- Dumped by pg_dump version 17.2 (Debian 17.2-1.pgdg120+1)

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
-- Name: capitalize_name_and_surname(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.capitalize_name_and_surname() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.name := INITCAP(NEW.name); 
    NEW.surname := INITCAP(NEW.surname);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.capitalize_name_and_surname() OWNER TO root;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: entry_list; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.entry_list (
    id integer NOT NULL,
    user_name character varying(100) NOT NULL,
    entry_id bigint NOT NULL,
    location character varying(100) NOT NULL,
    amount integer,
    id_assigned_by integer NOT NULL
);


ALTER TABLE public.entry_list OWNER TO root;

--
-- Name: entry_list_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.entry_list_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.entry_list_id_seq OWNER TO root;

--
-- Name: entry_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.entry_list_id_seq OWNED BY public.entry_list.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.roles OWNER TO root;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO root;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: user_roles; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.user_roles (
    user_id integer NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.user_roles OWNER TO root;

--
-- Name: users; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    id_user_details integer DEFAULT 0 NOT NULL,
    session_token character varying(255) DEFAULT NULL::character varying,
    is_blocked boolean DEFAULT false
);


ALTER TABLE public.users OWNER TO root;

--
-- Name: users_details; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.users_details (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    surname character varying(100) NOT NULL
);


ALTER TABLE public.users_details OWNER TO root;

--
-- Name: users_details_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.users_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_details_id_seq OWNER TO root;

--
-- Name: users_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.users_details_id_seq OWNED BY public.users_details.id;


--
-- Name: users_entry; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.users_entry (
    id_user integer NOT NULL,
    id_entry integer NOT NULL
);


ALTER TABLE public.users_entry OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: view_blocked_users; Type: VIEW; Schema: public; Owner: root
--

CREATE VIEW public.view_blocked_users AS
 SELECT u.email,
    ud.name,
    ud.surname
   FROM (public.users u
     JOIN public.users_details ud ON ((u.id_user_details = ud.id)))
  WHERE (u.is_blocked = true);


ALTER VIEW public.view_blocked_users OWNER TO root;

--
-- Name: view_unblocked_users; Type: VIEW; Schema: public; Owner: root
--

CREATE VIEW public.view_unblocked_users AS
 SELECT u.email,
    ud.name,
    ud.surname
   FROM (public.users u
     JOIN public.users_details ud ON ((u.id_user_details = ud.id)))
  WHERE (u.is_blocked = false);


ALTER VIEW public.view_unblocked_users OWNER TO root;

--
-- Name: entry_list id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entry_list ALTER COLUMN id SET DEFAULT nextval('public.entry_list_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: users_details id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_details ALTER COLUMN id SET DEFAULT nextval('public.users_details_id_seq'::regclass);


--
-- Data for Name: entry_list; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.entry_list (id, user_name, entry_id, location, amount, id_assigned_by) FROM stdin;
218	Mirosław Kowalski	98778	PG-192	4	15
220	Mirosław Kowalski	4335619141346	ABC-73	-15	15
215	Admin Mk18	23122024	XYZ	15	14
219	Admin Mk18	23122024	TYT123	-4	14
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.roles (id, name) FROM stdin;
1	admin
2	worker
\.


--
-- Data for Name: user_roles; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.user_roles (user_id, role_id) FROM stdin;
14	1
15	2
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.users (id, email, password, id_user_details, session_token, is_blocked) FROM stdin;
15	miron@gmail.pl	$2y$12$.J5AM9MniTG.6GrramX4DunpHDTB1mc1i9Mjt5crQRJMKHFle0vie	15	\N	f
14	mk@gmail.com	$2y$12$dxSh3t/icr6lAHzhWJWutOaJtzN3uwgbkNaLBNlLcKEbPkSaQ3XA6	14	\N	f
\.


--
-- Data for Name: users_details; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.users_details (id, name, surname) FROM stdin;
15	Mirosław	Kowalski
14	Admin	Mk18
\.


--
-- Data for Name: users_entry; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.users_entry (id_user, id_entry) FROM stdin;
\.


--
-- Name: entry_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.entry_list_id_seq', 221, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.roles_id_seq', 1, false);


--
-- Name: users_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.users_details_id_seq', 54, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.users_id_seq', 49, true);


--
-- Name: entry_list entry_list_pk; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entry_list
    ADD CONSTRAINT entry_list_pk PRIMARY KEY (id);


--
-- Name: roles roles_pk; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pk PRIMARY KEY (id);


--
-- Name: user_roles user_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_pkey PRIMARY KEY (user_id, role_id);


--
-- Name: users_details users_details_pk; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_details
    ADD CONSTRAINT users_details_pk PRIMARY KEY (id);


--
-- Name: users users_pk; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk PRIMARY KEY (id);


--
-- Name: users_details capitalize_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER capitalize_trigger BEFORE INSERT OR UPDATE ON public.users_details FOR EACH ROW EXECUTE FUNCTION public.capitalize_name_and_surname();


--
-- Name: users details_users___fk; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT details_users___fk FOREIGN KEY (id_user_details) REFERENCES public.users_details(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: entry_list entry_list_users_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entry_list
    ADD CONSTRAINT entry_list_users_id_fk FOREIGN KEY (id_assigned_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users_entry entry_users_entry___fk; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_entry
    ADD CONSTRAINT entry_users_entry___fk FOREIGN KEY (id_entry) REFERENCES public.entry_list(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_roles user_roles_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: user_roles user_roles_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users_entry user_users_entry___fk; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_entry
    ADD CONSTRAINT user_users_entry___fk FOREIGN KEY (id_user) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

