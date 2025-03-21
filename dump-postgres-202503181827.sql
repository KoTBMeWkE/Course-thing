PGDMP  5                    }            postgres    17.4    17.4 "    ?           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            @           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            A           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            B           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'ru-RU';
    DROP DATABASE postgres;
                     postgres    false            C           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4930                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                     postgres    false            D           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                        postgres    false    6            E           0    0    SCHEMA public    ACL     Q   REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;
                        postgres    false    6            �            1259    24874    contests    TABLE       CREATE TABLE public.contests (
    id_contest integer NOT NULL,
    title character varying NOT NULL,
    date date NOT NULL,
    description text NOT NULL,
    subject character varying NOT NULL,
    winning_student integer,
    teacher_id integer NOT NULL
);
    DROP TABLE public.contests;
       public         heap r       postgres    false    6            �            1259    16557    groups    TABLE     �   CREATE TABLE public.groups (
    id_group integer NOT NULL,
    study_year_start integer NOT NULL,
    students_amount integer NOT NULL,
    group_name character varying NOT NULL
);
    DROP TABLE public.groups;
       public         heap r       postgres    false    6            �            1259    24881    student_contests    TABLE     k   CREATE TABLE public.student_contests (
    student_id integer NOT NULL,
    contest_id integer NOT NULL
);
 $   DROP TABLE public.student_contests;
       public         heap r       postgres    false    6            �            1259    24744    students    TABLE     �  CREATE TABLE public.students (
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
    DROP TABLE public.students;
       public         heap r       postgres    false    6            �            1259    24731    teachers    TABLE     r  CREATE TABLE public.teachers (
    id_prep integer NOT NULL,
    name character varying NOT NULL,
    surname character varying NOT NULL,
    lastname character varying NOT NULL,
    group_id integer,
    phone_num character varying NOT NULL,
    email character varying NOT NULL,
    CONSTRAINT teachers_id_prep_check CHECK (((id_prep >= 1) AND (id_prep <= 10000)))
);
    DROP TABLE public.teachers;
       public         heap r       postgres    false    6            �            1259    24814    users    TABLE     �  CREATE TABLE public.users (
    id_user integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(20) NOT NULL,
    student_id integer,
    teacher_id integer,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY (ARRAY[('admin'::character varying)::text, ('teacher'::character varying)::text, ('student'::character varying)::text])))
);
    DROP TABLE public.users;
       public         heap r       postgres    false    6            ;          0    24874    contests 
   TABLE DATA           n   COPY public.contests (id_contest, title, date, description, subject, winning_student, teacher_id) FROM stdin;
    public               postgres    false    222   �*       7          0    16557    groups 
   TABLE DATA           Y   COPY public.groups (id_group, study_year_start, students_amount, group_name) FROM stdin;
    public               postgres    false    218   3.       <          0    24881    student_contests 
   TABLE DATA           B   COPY public.student_contests (student_id, contest_id) FROM stdin;
    public               postgres    false    223   U/       9          0    24744    students 
   TABLE DATA           x   COPY public.students (id_student, name, surname, lastname, group_id, birthdate, live_place, parent_contact) FROM stdin;
    public               postgres    false    220   r/       8          0    24731    teachers 
   TABLE DATA           `   COPY public.teachers (id_prep, name, surname, lastname, group_id, phone_num, email) FROM stdin;
    public               postgres    false    219   �9       :          0    24814    users 
   TABLE DATA           Z   COPY public.users (id_user, username, password, role, student_id, teacher_id) FROM stdin;
    public               postgres    false    221   D<       �           2606    24880    contests contests_pk 
   CONSTRAINT     Z   ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_pk PRIMARY KEY (id_contest);
 >   ALTER TABLE ONLY public.contests DROP CONSTRAINT contests_pk;
       public                 postgres    false    222            �           2606    16563    groups groups_pk 
   CONSTRAINT     T   ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pk PRIMARY KEY (id_group);
 :   ALTER TABLE ONLY public.groups DROP CONSTRAINT groups_pk;
       public                 postgres    false    218            �           2606    24885 &   student_contests student_contests_pkey 
   CONSTRAINT     x   ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_pkey PRIMARY KEY (student_id, contest_id);
 P   ALTER TABLE ONLY public.student_contests DROP CONSTRAINT student_contests_pkey;
       public                 postgres    false    223    223            �           2606    24751    students students_pk 
   CONSTRAINT     Z   ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pk PRIMARY KEY (id_student);
 >   ALTER TABLE ONLY public.students DROP CONSTRAINT students_pk;
       public                 postgres    false    220            �           2606    24738    teachers teachers_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pk PRIMARY KEY (id_prep);
 >   ALTER TABLE ONLY public.teachers DROP CONSTRAINT teachers_pk;
       public                 postgres    false    219            �           2606    24819    users users_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    221            �           2606    24906    contests contests_students_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_students_fk FOREIGN KEY (winning_student) REFERENCES public.students(id_student);
 G   ALTER TABLE ONLY public.contests DROP CONSTRAINT contests_students_fk;
       public               postgres    false    220    4759    222            �           2606    24896    contests contests_teachers_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.contests
    ADD CONSTRAINT contests_teachers_fk FOREIGN KEY (teacher_id) REFERENCES public.teachers(id_prep);
 G   ALTER TABLE ONLY public.contests DROP CONSTRAINT contests_teachers_fk;
       public               postgres    false    219    4757    222            �           2606    24820    users fk_students    FK CONSTRAINT     ~   ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_students FOREIGN KEY (student_id) REFERENCES public.students(id_student);
 ;   ALTER TABLE ONLY public.users DROP CONSTRAINT fk_students;
       public               postgres    false    220    4759    221            �           2606    24825    users fk_teachers    FK CONSTRAINT     {   ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_teachers FOREIGN KEY (teacher_id) REFERENCES public.teachers(id_prep);
 ;   ALTER TABLE ONLY public.users DROP CONSTRAINT fk_teachers;
       public               postgres    false    221    4757    219            �           2606    24891 1   student_contests student_contests_contest_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_contest_id_fkey FOREIGN KEY (contest_id) REFERENCES public.contests(id_contest);
 [   ALTER TABLE ONLY public.student_contests DROP CONSTRAINT student_contests_contest_id_fkey;
       public               postgres    false    223    4763    222            �           2606    24886 1   student_contests student_contests_student_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.student_contests
    ADD CONSTRAINT student_contests_student_id_fkey FOREIGN KEY (student_id) REFERENCES public.students(id_student);
 [   ALTER TABLE ONLY public.student_contests DROP CONSTRAINT student_contests_student_id_fkey;
       public               postgres    false    220    223    4759            �           2606    24830    students students_groups_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_groups_fk FOREIGN KEY (group_id) REFERENCES public.groups(id_group);
 E   ALTER TABLE ONLY public.students DROP CONSTRAINT students_groups_fk;
       public               postgres    false    218    4755    220            �           2606    24835    teachers teachers_groups_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_groups_fk FOREIGN KEY (group_id) REFERENCES public.groups(id_group);
 E   ALTER TABLE ONLY public.teachers DROP CONSTRAINT teachers_groups_fk;
       public               postgres    false    218    4755    219            ;   6  x��VIr�0<��LA�^>��1Rd�NyQ�O)'��rM�L����� �"�H�E,=���e�_��[�2c34��ueFfLC�(�,��D�$���^�)=J�U��#��,���ܙ{���� j�m�h�e���)L�ODS��K^�T��^�����M*��I�E����� ��B\s��+L�@���N�G�w{�q��Jϵ�m�o���1�5D��[�7���>��q�-�����Z�i�G���}"��+]Y��U��A:���`�c�D���g�e&ː5p��m�
 'ӓ4� �X6�-�]��ˤ*�\��L�qz�_J�Q�
�'l6 b&=J)/3$�̢��;8�|���yd�0E�W�I�x��9�>5:@b��P<އ_	Lk(���_N��<��A,��>j�� ĭO����KyukSO����[�g����ۤ������M�V�e��F`��ɳ�M��@�ɦ�C���ˮ�Ꮁn�|�QAaO�c�V���aø&���rU,��t[�r��Wa�r��}.�c���X�<P�Z�R�!����,�B�Cf�,�''4���i���:�G�E�Lّ9jGʀ�p���Y�%O�+ޔ�My�t>mK�K��f�uo�h�=�&��W�9����.��>�ǂ�z>�wE#{؇=ceB�W�:���`΃�n���O� ��]�K/t�r���W��1
 �6���Y���$E:���
K�ܳ�3�Z�щK]���������SE�Wӓ��U�]�&s�l�]�����
�2����&7����O��t�B��n4.      7     x�mQKN�0]ۧ�	P��.&iT@*�v�M�#�Ѐ���
oně|�ya�h�g�#/��D��j���hQ���̫�5[�Դ�bR%{tR���.�����#�p�' ��� �R��k�Q��UQ��ɞ�pֳ���-[���>����i�ҙ�@6n������딼'7(i+�����{6;���|��0jT�ft<�?<�Ӡ���3�]��d�$B?�S��K�����dW��Ü��DE�gj-�����+P��}�����*;�핵�pFV      <      x������ � �      9   
  x���ێ���GO��	
,��.yK�	� �v�bEA�܎l-��J�W�y��{�..�3ݽ�ڲ5�ٚb���Ev��p�:|�_���n�=}1�8���/}������t<���2i���������|���.}�7Ç�7p����U���7  �zL�7�q�����)E��Ϧ?��0N�rw��a�.��L
�H��9��ӗ�??�êV)%袰�0|�o=�^R�wcj��^�9߷�����8�-^9�;|���&����|=�%��Z�E����0|������<��c?�_:�)0��o�FL���#�p�0|?ܟ^�m�t��B�O�ǵ�9��O����?Ny�i����xWm	i�tQH7���)÷�|)g���W���"�B�s�o�#��Ϸg�JH眠�BzRz���(�$��n�ƿM"K+qK9�)�������ii�������(d��1I�GJ������J���`�|�3L��qx��v0��!A�I�ߏ9~��H��yJ��L��C�2�Z��_{L������X��o��$�&J�%ޯO������J�I���qY}*��t���x�����!~�+%��"�޻{��C� �Я(Q3%j��f.�%�9�>�"J�xȚ���a\�ӫ����Nh٬L�1�*����=���1��oh-HVY�gy�Y�Z�f��*Q=���5�
��i�ψ��
���Py�������X��g�A>�f��S��Y�4$5���T����r�S� �E��PrY�5��pT�XhH�#I�>e������&5v�[�(?�iH�r;�J�G�LQ�|eI�5��c�ƴn/���쓱_�GH�!�g�;2������!ƻ:'D��kXI�\IM��3^���LR�WW-d>uU���"��I�F����W�)-�]T�R��N�(���kƜ�m!�Y�T,���4'�E5�NO����mS��p:�jHb���\*�+jrXj��ae9!p5$I�F�����mOˎӂl��v���ֵ�2�K��91�RԐ��>q7n#V�oltP�U*j$Ǣ��]]�1����Xur��J��,�5��o�YK�	je(���ZU�E�3�F�Dn������K��ñV�A	KZ?2�nK�Kؕf�'e�94 5��0�툘��(�X����*m�X��(��rX ��`�3���b����B;	���&�6ېq�T��^h�Ai�B�C�f�[�v���z$"�ō�lvE��*�l�u:V{)1��u~2�a�C6��B�t c`��˞�p\�v�"�}�3���c�8*�.��^����1��P�4!ʘ=n2�e��0�CCa�*�x�w�ߖ�����X�����[��j��qh(L��e�Zϵʺ�S�q�0M	[�/*8TlH(�Xq�ՖnY]Pr��Rhb�����d$������и&JY�b��B��k��B��|ت�v[z���*N
:��z��rr�S�՜�,ʚm�k3�e[��p>h\db��Oa�R]��ל e�&�MG]!;��Lx�~�ݚ��c�924�2�Ɇ}v���m���!`ٸ�r/ �F�#�!L9��p�6|Nr`�0�`�ժk����(aW��c��䞞���0zz���Z�-P���i�:æ�������`g8!򁹳{,�&K�(�Y�������6Ý=�l����0xS�P����^<�p����%N���l��E� م�QX���O�ýp��"�������f�*Y/o��䔰 ,1���-Ͳ�m�<p^X%,1ʫu~�
��:��s�ja�L^�uڐ	����kk����<�����=o8.�ŷ[��\�T�[���Tϻ'����e9;���"\y��r�|��=ǇE��|Xo��ӋA�
Q8���k��<�}(��a�p�� ךm�)w��� 9 Gx
��nUF��)9 ��S����f�!#��:(N��#T�<L����U��7\�8^Po0��NcA����_Pr��4@��6�ՙ�}=;Έ<��S�m�`ϙ�P�D���my��9{��E��F�e�-GN/�'>E��o�a�Z:������[������#pXx%<!*��8LV���84��P����5���4G�7������V�z�h84����Z�uj:Z��Q��&ۥ9:�U�X���6&�K;��9.�T���7L���PA#�@���ƥBÂZꍜA��P�q�ۭj�Oc�SrTAQ���ԕ��Ӛ���gP"h
���h��aB|NVs�A�`(��	�-�n��\
Đ�����i)hPk�SV����(���)�0�����8��S̝CS�p���������k��f�Z���s5�I��v�Дo8Q�F/Ly��17��R>|.E$z��������x�`��T�nGPvF��X�zv�����x�`��T.�["�j�3��}���S��]@5��"j���)u�v����x�`� ��fOPxF�V�k��iIMMW��1�����)*igPMx�h8��5@e��$�j��<�j��n?���g���ٳg��B�k      8   �  x�}��N�@��'O�e�j�����	��l�Y��7j�*�7H�����,�a��:;6���4�"'�����W�����9�O���j_ީ]y'���Sy#K|pK�r0ᅿ�l<?_3M�Q�E̍Y�q2�/�[m���H���#I\�=�c����U�,���d��+]�	˲�����"K�yG�y"s�{����x���=űz'/�c����۶D<���f'٬���B��-�&��zbr�H�`N�ۋ�1��9�è`Y�0f�$�ø R�s}��R� ���L5��i$\�eT��Snl.R�x�!]�����6�I-pI�yy�Ǩ`&ҩXJ��0�j<��pF��K�Y6��L�<�Z�xn#��>��Ŧi0M�@�xF�Qu���*����/�.t��h4bT���x-&��1�G_�:P�Ց����R<����o�
2�\FD�Q`�a'�};�q�]r�@^�1̃����}�#��b ��������x��Bɷ�i��L�����������уz2-g$�"������r M"�i�,�����4��?N{�F��0�1�VHOlөH��Sxj���Z�0���e��f����Y���+�������{b;����y��s}�y�C��ff�� ���      :   �  x�U�[s�0��k��V��eU(*�
���3N�@"�
�~uvm�L��/y�p�jL�7�q��dQt�f�j+��NއT�7�#���x�6 q�jR���v{ujHd�?�Ǿ/�g@KqGqz�h�AB�v�:�����G`�[riΐ7ha�.�����u�y�u@MlZ�2O��8��Pt�"<>e0Ů�s7�Ӝ�+<�f�'����&�1{?E�]�Tg��8�-~���,2yO�*2�>�������縱�ؙ��f�M}����=�y?��q�̓:���E�Ĥ-_��B���R$.�tUt�)q�����;g�2W��W��ϐ�%A��]$o$ۙ�e"0O䞝geݛ��� og�~77�)�M���V5ר/{�$:��G2�������Im�q@t�׿i�U�.Ő��P�m��u�vKWݒ�@�\B�d)@'����%�]H?�["���g;9�x~�";c�SO�&�{��w��f4�u�\��9!�-XK�e�ֆn��]�`��{"���j��Jy�-=z�L�)l��=]~z�V�U1�
��4S������l'Z�
��'�I)
ќ]��nhR5s�2@~�3u�ݟy���.��װ��A�}�B�����L5�g/1����˖��l~?i~��X�{��{���}������Yhy��U��z�Gš��U�@F�nwz���;a��*�S��r�2�g²�_C�     