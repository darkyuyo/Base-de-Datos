import pyodbc
import random
import datetime

conexion = pyodbc.connect("DRIVER={Oracle en OraDB18Home1};DBQ=XE;Uid=alfredo;Pwd=waveboard")
#crea la tabla de poyo
def crear_poyo(conn):
    cur = conn.cursor()
    cur.execute("DROP TABLE POYO")
    cur.execute("""CREATE TABLE POYO(  
                    pokedex NUMBER,
                    nombre VARCHAR2(40 CHAR),
                    type1 VARCHAR2(10 CHAR),
                    type2 VARCHAR2(10 CHAR),
                    hp_total NUMBER,
                    legendary NUMBER
                )""")
    cur.commit()
#rellena la tabla de poyo
def rellenar_poyo(conn):
    cur = conn.cursor()
    archivo=open("pokemon.csv")
    for linea in archivo:
        linea=linea.strip().split(",")
        if linea[0]!="#":
            if linea[12]=="True":
                linea[12]=1
            else:
                linea[12]=0
            cur.execute("INSERT INTO POYO VALUES (?,?,?,?,?,?)",(linea[0],linea[1],linea[2],linea[3],linea[5],linea[12]))
    archivo.close()
    cur.commit()
#crea sansanito
def crear_sansanito(conn):
    cur = conn.cursor()
    cur.execute("DROP TABLE SANSANITOPOKEMON")
    cur.execute("""CREATE TABLE SANSANITOPOKEMON(
                pkmn_id NUMBER GENERATED BY DEFAULT ON NULL AS IDENTITY,
                pkmn_pokedex NUMBER,
                pkmn_nombre VARCHAR2(40 CHAR),
                pkmn_type1 VARCHAR2(10 CHAR),
                pkmn_type2 VARCHAR2(10 CHAR),
                pkmn_hp NUMBER,
                pkmn_hp_max NUMBER, 
                pkmn_legendary NUMBER,
                pkmn_estado VARCHAR2(10 CHAR),
                pkmn_date TIMESTAMP,
                pkmn_prioridad NUMBER
            )""")
    cur.commit()
#rellena la tabla de sansanito al azar
def rellenar_sansanito(conn):
    cur = conn.cursor()
    #la capacidad que llevamos
    capacidad=0
    #lista de nombres de legendarios dentro de la tabla
    legendarios=[]
    estados=["envenenado","paralizado","quemado","domido","congelado"]
    while capacidad<50:
        #toma un pokemon al azar de poyo
        cur.execute("SELECT * FROM POYO SAMPLE(1);")
        #lo guardamos en pokemon los datos
        pokemon=cur.fetchone()
        #si es 1 es legendarios
        if(pokemon[5]==1):
            #si la capacidad tien mas de 45 vuelve a otor pokemon al azar
            if(capacidad>=45):
                continue
            #si ya esta en la lista de nombres de legendarios, vuelve a un pokemon al azar
            elif (pokemon[1] in legendarios):
                continue
            else:
                #se agrega a la lista de nombres y aumenta capacidad en 5
                legendarios.append(pokemon[1])
                capacidad=capacidad+5
        else:
            #es normal
            capacidad=capacidad+1
        #se crean los datos al azar
        pokemon_hp=random.randint(0,pokemon[4])
        estado=random.randint(0,5)
        pokemon_prioridad=pokemon[4]-pokemon_hp
        pokemon_estado="normal"
        if(estado!=5):
            #si el estado no es normal, se suma 10 a la prioridad
            pokemon_estado=estados[estado]
            pokemon_prioridad=pokemon_prioridad+10
        #se agrega a sansanito
        cur.execute("INSERT INTO SANSANITOPOKEMON (pkmn_pokedex,pkmn_nombre,pkmn_type1,pkmn_type2,pkmn_hp,pkmn_hp_max,pkmn_legendary,pkmn_estado,pkmn_date,pkmn_prioridad) VALUES (?,?,?,?,?,?,?,?,LOCALTIMESTAMP,?)",(pokemon[0],pokemon[1],pokemon[2],pokemon[3],pokemon_hp,pokemon[4],pokemon[5],pokemon_estado,pokemon_prioridad))
        cur.commit()
 #te devuelve una vista de los 10 de mayor prioridad          
def tenmayorprioridad(conn):
    cur = conn.cursor()
    cur.execute("""CREATE OR REPLACE VIEW DIEZMAYORPRIORIDAD AS SELECT * 
                    FROM (
                        SELECT * FROM SANSANITOPOKEMON 
                        ORDER BY pkmn_prioridad DESC
                        )
                    WHERE rownum < 11
                """)
    cur.execute("SELECT * FROM SANSANITOPOKEMON ORDER BY pkmn_prioridad DESC")
    for i in range(0,10):
        print(cur.fetchone())
 #te devuelve una vista de los 10 de menor prioridad    
def tenmenorprioridad(conn):
    cur = conn.cursor()
    cur.execute("""CREATE OR REPLACE VIEW DIEZMENORPRIORIDAD AS SELECT * 
                    FROM (
                        SELECT * FROM SANSANITOPOKEMON 
                        ORDER BY pkmn_prioridad 
                        )
                    WHERE rownum < 11
                """)    
    cur.execute("SELECT * FROM SANSANITOPOKEMON ORDER BY pkmn_prioridad")
    for i in range(0,10):
        print(cur.fetchone())   
 #te devuelve una vista de los de un estado especifico 
def estadoespecifico(conn):
    cur = conn.cursor()
    estado=input("Ingrese el estado a buscar ")
    cur.execute(f"""CREATE OR REPLACE VIEW ESTADOESPECIFICO AS SELECT * 
                    FROM (
                        SELECT * FROM SANSANITOPOKEMON 
                        )
                    WHERE pkmn_estado = '{estado}'
                """)                                                                           
    cur.execute("SELECT * FROM SANSANITOPOKEMON WHERE pkmn_estado=?",estado)
    print(cur.fetchall())
#te devuelve una vista de los legendarios  
def pokemonlegendarios(conn):
    cur = conn.cursor()
    cur.execute("""CREATE OR REPLACE VIEW LEGENDARIOS AS SELECT * 
                    FROM (
                        SELECT * FROM SANSANITOPOKEMON 
                        )
                    WHERE pkmn_legendary=1
                """)        
    cur.execute("SELECT * FROM SANSANITOPOKEMON WHERE pkmn_legendary =1")
    print(cur.fetchall())
 #te devuelve el con mas tiempo en print  
def mastiempo(conn):
    cur = conn.cursor()
    cur.execute("SELECT * FROM SANSANITOPOKEMON ORDER BY pkmn_date")
    print(cur.fetchone())
#con sql hice un select de las ordenados de las repeticiones y saque el de mas arriba
def masrepetido(conn):
    cur = conn.cursor()
    cur.execute(""" SELECT pkmn_nombre, COUNT( pkmn_nombre ) AS repeticiones
                    FROM  SANSANITOPOKEMON
                    GROUP BY pkmn_nombre
                    ORDER BY repeticiones DESC """)
    print("El pokemon mas repetido es",cur.fetchone()[0],"con",cur.fetchone()[1],"repeticiones")

 #te devuelve una vista de los todos los pokemon ordenada por prioridad   
def mayorprioridad(conn):
    cur = conn.cursor()
    cur.execute("""CREATE OR REPLACE VIEW ORDENADOSPRIORIDADPKMN AS
                SELECT * FROM
                    (SELECT pkmn_nombre, pkmn_hp, pkmn_hp_max, pkmn_prioridad
                    FROM  SANSANITOPOKEMON ORDER BY pkmn_prioridad DESC)""")
#inserta un pokemon en sansanito con muchas restricciones
def create(conn):
    cur = conn.cursor()
    #cantidad de legendarios en sansanito
    cant_legendarios=0
    #capacidad que queda
    capacidad_dispo=50
    cur.execute("""SELECT pkmn_legendary FROM SANSANITOPOKEMON""")
    #lista que nos dice el valor de si es legendario o no de cada pokemon
    legendarios=cur.fetchall()
    for cosa in legendarios:
        if cosa[0]==0.0:
            #si es 0 es porque no es legendario,disminuyendo la capacidad en 1
            capacidad_dispo=capacidad_dispo-1
        else:
            #si es 1 es porque es legendario,disminuyendo la capacidad en 5 y sumando 1 al contador de legendarios
            capacidad_dispo=capacidad_dispo-5
            cant_legendarios=cant_legendarios+1
    nombre = input("Ingrese el nombre del pokemon a crear de la tabla POYO ")
    #buscamos en la tabla poyo con el nombre
    cur.execute("""SELECT * FROM POYO WHERE nombre=?;""",(nombre))
    #lo guardamos en variable pokemon
    pokemon=cur.fetchone()
    print(pokemon)
    pokemon_hp = int(input("Ingrese el HP actual "))
    pokemon_estado = input("Ingrese el ESTADO a modificar ")
    pokemon_prioridad=pokemon[4]-pokemon_hp
    if pokemon_estado!="normal":
        pokemon_prioridad=pokemon_prioridad+10
    #si es legendario buscamos que no este el nombre del pkmn en sansanito
    if pokemon[5]==1.0:
        cur.execute("""SELECT * FROM SANSANITOPOKEMON WHERE pkmn_nombre=?;""",(nombre))
        #si es 0, no estaba su nombre
        if len(cur.fetchall())==0:
            if capacidad_dispo>=5:
                cur.execute("INSERT INTO SANSANITOPOKEMON (pkmn_pokedex,pkmn_nombre,pkmn_type1,pkmn_type2,pkmn_hp,pkmn_hp_max,pkmn_legendary,pkmn_estado,pkmn_date,pkmn_prioridad) VALUES (?,?,?,?,?,?,?,?,LOCALTIMESTAMP,?)",(pokemon[0],pokemon[1],pokemon[2],pokemon[3],pokemon_hp,pokemon[4],pokemon[5],pokemon_estado,pokemon_prioridad))
                print("Se agrego al pokemon legendario",pokemon[1])
                cur.commit()
            #si queda menos de 5 de capacidad habra que sacar un legendario de menor prioridad
            else:
                #si no habian legendarios en sansanito y estaba la capacidad llena, no se puede meter
                if cant_legendarios>0:
                    cur.execute("""SELECT * FROM SANSANITOPOKEMON
                                WHERE pkmn_legendary=1
                                ORDER BY pkmn_prioridad ASC""")
                    pkmn=(cur.fetchone())
                    #dejamos al legendario que tenga mayor prioridad entre el que queremos meter y el legendario que tiene menor prioridad
                    if pokemon_prioridad>=pkmn[10]:
                        cur.execute("""DELETE FROM SANSANITOPOKEMON
                                    WHERE pkmn_id=?""",pkmn[0])
                        print("Se elimino el pokemon",pkmn[2])
                        cur.execute("INSERT INTO SANSANITOPOKEMON (pkmn_pokedex,pkmn_nombre,pkmn_type1,pkmn_type2,pkmn_hp,pkmn_hp_max,pkmn_legendary,pkmn_estado,pkmn_date,pkmn_prioridad) VALUES (?,?,?,?,?,?,?,?,LOCALTIMESTAMP,?)",(pokemon[0],pokemon[1],pokemon[2],pokemon[3],pokemon_hp,pokemon[4],pokemon[5],pokemon_estado,pokemon_prioridad))
                        print("Se agrego al pokemon legendario",pokemon[1])
                        cur.commit()
                    else:
                        print("Tienen mayor prioridad los demas legendarios")
                else:
                    print("No habian legendarios en SANSANITOPOKEMON")
        else:
            print("Ya estaba ",nombre,"en SANSANITOPOKEMON")
            return
    #es un pokemon no legendario
    else:
        #si esta lleno, hay que eliminar a uno normal
        if capacidad_dispo==0:
            cur.execute("""SELECT * FROM SANSANITOPOKEMON
                        WHERE pkmn_legendary=0
                        ORDER BY pkmn_prioridad ASC""")
            pkmn=(cur.fetchone())
            #comparamos al de menor prioridad con el que queremos meter
            if pokemon_prioridad>=pkmn[10]:
                cur.execute("""DELETE FROM SANSANITOPOKEMON
                            WHERE pkmn_id=?""",pkmn[0])
                print("Se elimino el pokemon",pkmn[2])
                cur.execute("INSERT INTO SANSANITOPOKEMON (pkmn_pokedex,pkmn_nombre,pkmn_type1,pkmn_type2,pkmn_hp,pkmn_hp_max,pkmn_legendary,pkmn_estado,pkmn_date,pkmn_prioridad) VALUES (?,?,?,?,?,?,?,?,LOCALTIMESTAMP,?)",(pokemon[0],pokemon[1],pokemon[2],pokemon[3],pokemon_hp,pokemon[4],pokemon[5],pokemon_estado,pokemon_prioridad))
                print("Se agrego al pokemon",pokemon[1])
                cur.commit() 
            else:
                print("Estimado pokemon, te falta prioridad")
        else:
            #si no esta lleno, se mete
            cur.execute("INSERT INTO SANSANITOPOKEMON (pkmn_pokedex,pkmn_nombre,pkmn_type1,pkmn_type2,pkmn_hp,pkmn_hp_max,pkmn_legendary,pkmn_estado,pkmn_date,pkmn_prioridad) VALUES (?,?,?,?,?,?,?,?,LOCALTIMESTAMP,?)",(pokemon[0],pokemon[1],pokemon[2],pokemon[3],pokemon_hp,pokemon[4],pokemon[5],pokemon_estado,pokemon_prioridad))
            print("Se agrego al pokemon",pokemon[1])
            cur.commit()                 

#busca por id en sansanito y printea el pokemon
def read(conn):
    cur = conn.cursor() 
    id = input("Ingrese el ID del pokemon a buscar ")
    cur.execute("""SELECT * FROM SANSANITOPOKEMON WHERE pkmn_id = ?;""",(id))
    print(cur.fetchone())
#definimos primary key al id en sansanito
def pkSANSANITO(conn):
    cur = conn.cursor()
    cur.execute("""ALTER TABLE SANSANITOPOKEMON
                ADD CONSTRAINT pk_sansanito PRIMARY KEY (pkmn_nombre);""")
    cur.commit()
#definimos primary key al nombre en poyo
def pkPOYO(conn):
    cur = conn.cursor()
    cur.execute("""ALTER TABLE POYO
                ADD CONSTRAINT pk_poyo PRIMARY KEY (nombre);""")
    cur.commit()
#modificamos el hp y estado de un pokemon en sansanito
def update(conn):
    cur = conn.cursor() 
    id = input("Ingrese el ID del pokemon a modificar ")
    hp = input("Ingrese el HP a modificar ")
    estado = input("Ingrese el ESTADO a modificar ")
    cur.execute("""UPDATE SANSANITOPOKEMON
                    SET pkmn_hp=?, pkmn_estado = ?
                    WHERE pkmn_id = ?;""",(hp,estado,id))
    trigger(conn)
    cur.execute("""SELECT * FROM SANSANITOPOKEMON WHERE pkmn_id = ?;""",(id))
    cur.commit()
    print(cur.fetchone())     
#mediante el id borramos un pokemon de sansanito
def delete(conn):
    cur = conn.cursor() 
    id = input("Ingrese el ID del pokemon a borrar ")
    cur.execute("""DELETE FROM SANSANITOPOKEMON
                WHERE pkmn_id=?""",(id))
    cur.commit()
#despues de un update, modificamos la prioridad de un pkmn
def trigger(conn):
    cur = conn.cursor() 
    cur.execute("""CREATE OR REPLACE TRIGGER TIGRE
                        BEFORE UPDATE OF pkmn_hp, pkmn_estado ON SANSANITOPOKEMON FOR EACH ROW
                        BEGIN
                            IF UPDATING THEN
                                IF :new.pkmn_estado ='normal' THEN
                                    :new.pkmn_prioridad:=:new.pkmn_hp_max - :new.pkmn_hp;
                                ELSE
                                    :new.pkmn_prioridad:=:new.pkmn_hp_max - :new.pkmn_hp + 10;
                                END IF;
                            END IF;
                        END;""")
    cur.commit()                

#printea las opciones
def printf():
    print("INGRESE EL NUMERO QUE DESEA HACER")
    print("1: Ingresar un pokemon")
    print("2: Los 10 Pokemon con mayor prioridad")
    print("3: Los 10 Pokemon con menor prioridad")
    print("4: Todos los Pokemon con un estado en especifico")
    print("5: Todos los Pokemon legendarios")
    print("6: El Pokemon que lleva mas tiempo ingresado")
    print("7: Nombre del Pokemon mas repetido")
    print("8: Nombre, HP actual, HP Max y prioridad de todos los Pokemon, ordenados por prioridad")
    print("9: CREATE")
    print("10: READ")
    print("11: UPDATE")
    print("12: DELETE")
    print("13: Salir")

#crear_sansanito(conexion)
#rellenar_sansanito(conexion)
opcion=0
while opcion!=13:
    printf()
    opcion=int(input("Ingrese un numero: "))
    if(opcion==1):
        create(conexion)
    elif(opcion==2):
        tenmayorprioridad(conexion)
        print("Se creo una vista")
    elif(opcion==3):
        tenmenorprioridad(conexion)
        print("Se creo una vista")
    elif(opcion==4):
        estadoespecifico(conexion)
        print("Se creo una vista")
    elif(opcion==5):
        pokemonlegendarios(conexion)
        print("Se creo una vista")
    elif(opcion==6):
        mastiempo(conexion)
    elif(opcion==7):
        masrepetido(conexion)
        print("Se creo una vista")
    elif(opcion==8):
        mayorprioridad(conexion)
        print("Se creo una vista")
    elif(opcion==9):
        create(conexion)
    elif(opcion==10):
        read(conexion)
    elif(opcion==11):
        update(conexion)
    elif(opcion==12):
        delete(conexion)
