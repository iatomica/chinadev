import sys
import pymysql
from pathlib import Path

# Parámetros de conexión sacados de la URL proporcionada
DB_CONFIG = {
    "host": "91.107.212.235",
    "port": 3333,
    "user": "root",
    "password": "Xog6QHn7IuOQNnMcPgVXhGirc1dlIRHWakRyygsPPqUzsgzaKL4lGZ0D75tG8npE",
    "database": "ezqstock",
    "charset": "utf8mb4",
    "autocommit": True,
}


def split_sql_statements(sql: str):
    """Divide un script SQL en sentencias individuales, respetando strings.

    Primero elimina las líneas de comentario que empiezan por -- o # para que
    no se mezclen con las sentencias (por ejemplo, comentarios justo antes de
    un CREATE TABLE).
    """

    # Quitar líneas de comentario completas
    filtered_lines = []
    for line in sql.splitlines(True):  # mantener saltos de línea
        stripped = line.lstrip()
        if stripped.startswith("--") or stripped.startswith("#"):
            continue
        filtered_lines.append(line)

    sql_clean = "".join(filtered_lines)

    statements = []
    statement = []
    in_string = False
    string_char = ""
    escape = False

    for ch in sql_clean:
        if in_string:
            statement.append(ch)
            if escape:
                escape = False
            elif ch == "\\":
                escape = True
            elif ch == string_char:
                in_string = False
        else:
            if ch in ("'", '"'):
                in_string = True
                string_char = ch
                statement.append(ch)
            elif ch == ";":
                current = "".join(statement).strip()
                if current:
                    statements.append(current)
                statement = []
            else:
                statement.append(ch)

    # Última sentencia si no termina en ;
    last = "".join(statement).strip()
    if last:
        statements.append(last)

    # Filtrar sentencias vacías
    cleaned = []
    for stmt in statements:
        stripped = stmt.strip()
        if not stripped:
            continue
        cleaned.append(stripped)

    return cleaned


def main():
    if len(sys.argv) != 2:
        print("Uso: python run_sql_file.py /ruta/al/archivo.sql")
        sys.exit(1)

    sql_path = Path(sys.argv[1])
    if not sql_path.is_file():
        print(f"No se encontró el archivo: {sql_path}")
        sys.exit(1)

    sql_text = sql_path.read_text(encoding="utf-8", errors="ignore")
    statements = split_sql_statements(sql_text)

    print(f"Ejecutando {len(statements)} sentencias desde {sql_path}...")

    # Mostrar un resumen de las primeras sentencias para depuración
    print("Primeras 10 sentencias detectadas:")
    for i, s in enumerate(statements[:10], start=1):
        one_line = " ".join(s.splitlines())
        print(f"[{i}] {one_line[:200]}")

    conn = pymysql.connect(**DB_CONFIG)
    try:
        with conn.cursor() as cursor:
            # Desactivar chequeos de claves foráneas durante la importación
            try:
                cursor.execute("SET FOREIGN_KEY_CHECKS=0;")
                print("Chequeos de claves foráneas desactivados para la sesión.")
            except Exception as e:
                print("No se pudo desactivar FOREIGN_KEY_CHECKS (continuando igual):", e)

            for idx, stmt in enumerate(statements, start=1):
                try:
                    cursor.execute(stmt)
                    # Opcional: mostrar progreso básico
                    if idx % 100 == 0:
                        print(f"{idx} sentencias ejecutadas...")
                except Exception as e:
                    print("\nERROR al ejecutar la sentencia #{}:".format(idx))
                    print(stmt[:1000])  # Mostrar los primeros caracteres
                    print("\nDetalle del error:", e)
                    sys.exit(1)
        # Si autocommit=False, aquí haríamos conn.commit()
    finally:
        conn.close()

    print("\nImportación completada correctamente.")


if __name__ == "__main__":
    main()
