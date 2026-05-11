-- WARNING: This schema is for context only and is not meant to be run.
-- Table order and constraints may not be valid for execution.

CREATE TABLE public.cache (
  key character varying NOT NULL,
  value text NOT NULL,
  expiration integer NOT NULL,
  CONSTRAINT cache_pkey PRIMARY KEY (key)
);
CREATE TABLE public.cache_locks (
  key character varying NOT NULL,
  owner character varying NOT NULL,
  expiration integer NOT NULL,
  CONSTRAINT cache_locks_pkey PRIMARY KEY (key)
);
CREATE TABLE public.canned_responses (
  id bigint NOT NULL DEFAULT nextval('canned_responses_id_seq'::regclass),
  title character varying NOT NULL,
  content text NOT NULL,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT canned_responses_pkey PRIMARY KEY (id)
);
CREATE TABLE public.failed_jobs (
  id bigint NOT NULL DEFAULT nextval('failed_jobs_id_seq'::regclass),
  uuid character varying NOT NULL UNIQUE,
  connection text NOT NULL,
  queue text NOT NULL,
  payload text NOT NULL,
  exception text NOT NULL,
  failed_at timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT failed_jobs_pkey PRIMARY KEY (id)
);
CREATE TABLE public.game_accounts (
  id bigint NOT NULL DEFAULT nextval('game_accounts_id_seq'::regclass),
  title character varying NOT NULL,
  description text NOT NULL,
  category character varying NOT NULL,
  price numeric NOT NULL,
  image_path character varying,
  status character varying NOT NULL DEFAULT 'available'::character varying CHECK (status::text = ANY (ARRAY['available'::character varying, 'sold'::character varying]::text[])),
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  images json,
  accounts json,
  username character varying,
  password character varying,
  hash_id character varying UNIQUE,
  CONSTRAINT game_accounts_pkey PRIMARY KEY (id)
);
CREATE TABLE public.job_batches (
  id character varying NOT NULL,
  name character varying NOT NULL,
  total_jobs integer NOT NULL,
  pending_jobs integer NOT NULL,
  failed_jobs integer NOT NULL,
  failed_job_ids text NOT NULL,
  options text,
  cancelled_at integer,
  created_at integer NOT NULL,
  finished_at integer,
  CONSTRAINT job_batches_pkey PRIMARY KEY (id)
);
CREATE TABLE public.jobs (
  id bigint NOT NULL DEFAULT nextval('jobs_id_seq'::regclass),
  queue character varying NOT NULL,
  payload text NOT NULL,
  attempts smallint NOT NULL,
  reserved_at integer,
  available_at integer NOT NULL,
  created_at integer NOT NULL,
  CONSTRAINT jobs_pkey PRIMARY KEY (id)
);
CREATE TABLE public.messages (
  id bigint NOT NULL DEFAULT nextval('messages_id_seq'::regclass),
  sender_id bigint NOT NULL,
  receiver_id bigint NOT NULL,
  message text,
  image_path character varying,
  is_read boolean NOT NULL DEFAULT false,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  account_id bigint,
  account_title character varying,
  is_auto_message boolean NOT NULL DEFAULT false,
  offer_price numeric,
  is_price_offer boolean NOT NULL DEFAULT false,
  offer_valid_until timestamp without time zone,
  CONSTRAINT messages_pkey PRIMARY KEY (id),
  CONSTRAINT messages_sender_id_foreign FOREIGN KEY (sender_id) REFERENCES public.users(id),
  CONSTRAINT messages_receiver_id_foreign FOREIGN KEY (receiver_id) REFERENCES public.users(id),
  CONSTRAINT messages_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.game_accounts(id)
);
CREATE TABLE public.migrations (
  id integer NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
  migration character varying NOT NULL,
  batch integer NOT NULL,
  CONSTRAINT migrations_pkey PRIMARY KEY (id)
);
CREATE TABLE public.orders (
  id bigint NOT NULL DEFAULT nextval('orders_id_seq'::regclass),
  user_id bigint NOT NULL,
  game_account_id bigint NOT NULL,
  order_number character varying NOT NULL UNIQUE,
  amount numeric NOT NULL,
  status character varying NOT NULL DEFAULT 'pending'::character varying CHECK (status::text = ANY (ARRAY['pending'::character varying, 'paid'::character varying, 'completed'::character varying, 'failed'::character varying, 'cancelled'::character varying]::text[])),
  payment_method character varying,
  notes text,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  account_username character varying,
  account_password character varying,
  CONSTRAINT orders_pkey PRIMARY KEY (id),
  CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT orders_game_account_id_foreign FOREIGN KEY (game_account_id) REFERENCES public.game_accounts(id)
);
CREATE TABLE public.password_reset_tokens (
  email character varying NOT NULL,
  token character varying NOT NULL,
  created_at timestamp without time zone,
  CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email)
);
CREATE TABLE public.reviews (
  id bigint NOT NULL DEFAULT nextval('reviews_id_seq'::regclass),
  user_id bigint NOT NULL,
  game_account_id bigint NOT NULL,
  order_id bigint,
  rating integer NOT NULL DEFAULT 5,
  comment text,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT reviews_pkey PRIMARY KEY (id),
  CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT reviews_game_account_id_foreign FOREIGN KEY (game_account_id) REFERENCES public.game_accounts(id),
  CONSTRAINT reviews_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id)
);
CREATE TABLE public.sessions (
  id character varying NOT NULL,
  user_id bigint,
  ip_address character varying,
  user_agent text,
  payload text NOT NULL,
  last_activity integer NOT NULL,
  CONSTRAINT sessions_pkey PRIMARY KEY (id)
);
CREATE TABLE public.settings (
  id bigint NOT NULL DEFAULT nextval('settings_id_seq'::regclass),
  key character varying NOT NULL UNIQUE,
  value text,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT settings_pkey PRIMARY KEY (id)
);
CREATE TABLE public.users (
  id bigint NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  name character varying NOT NULL,
  email character varying NOT NULL UNIQUE,
  email_verified_at timestamp without time zone,
  password character varying NOT NULL,
  remember_token character varying,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  role character varying NOT NULL DEFAULT 'user'::character varying,
  is_bot_active boolean NOT NULL DEFAULT true,
  hash_id character varying UNIQUE,
  google_id character varying UNIQUE,
  google_token character varying,
  google_refresh_token character varying,
  avatar character varying,
  CONSTRAINT users_pkey PRIMARY KEY (id)
);
CREATE TABLE public.wishlists (
  id bigint NOT NULL DEFAULT nextval('wishlists_id_seq'::regclass),
  user_id bigint NOT NULL,
  game_account_id bigint NOT NULL,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT wishlists_pkey PRIMARY KEY (id),
  CONSTRAINT wishlists_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT wishlists_game_account_id_foreign FOREIGN KEY (game_account_id) REFERENCES public.game_accounts(id)
);