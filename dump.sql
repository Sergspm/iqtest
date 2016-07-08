--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: iqtest_posts; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iqtest_posts (
    id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    homepage character varying(255) NOT NULL,
    text text NOT NULL,
    ip character varying(20) NOT NULL,
    user_agent character varying(255) NOT NULL,
    date_created integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.iqtest_posts OWNER TO postgres;

--
-- Name: iqtest_posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE iqtest_posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.iqtest_posts_id_seq OWNER TO postgres;

--
-- Name: iqtest_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE iqtest_posts_id_seq OWNED BY iqtest_posts.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iqtest_posts ALTER COLUMN id SET DEFAULT nextval('iqtest_posts_id_seq'::regclass);


--
-- Data for Name: iqtest_posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY iqtest_posts (id, user_name, email, homepage, text, ip, user_agent, date_created) FROM stdin;
1	admin	edetety@fdgdfg.rfg	test homepage	dsfsfgsdgdgf	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439058290
2	admin	edetety@fdgdfg.rfg	test homepage	dsfsfgsdgdgf	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439058315
3	admin	serghspm@inbox.ru	test homepage	sf',l;/mgf/sdlfmkg/sdfg	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439058924
4	sfszfsdf	serghspm@inbox.ru	dfasdfs	DFadfd	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439061900
5	sfszfsdf	serghspm@inbox.ru	dfasdfs	DFadfd	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439061918
6	sfszfsdf	serghspm@inbox.ru	dfasdfs	DFadfd	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439061924
7	sfszfsdf	serghspm@inbox.ru	dfasdfs	DFadfd	84.52.125.44	Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36	1439061932
8	йцуйуйц	sdfwefewf@weferf.tu	qweqwe	qweqweqwe	185.33.199.206	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36	1439205324
9	&lt;script&gt;alert('1');&lt;/script&gt;	ffasd@dfd.ru	куц	кцйу	185.33.199.206	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36	1439207027
\.


--
-- Name: iqtest_posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('iqtest_posts_id_seq', 9, true);


--
-- Name: iqtest_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iqtest_posts
    ADD CONSTRAINT iqtest_posts_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

