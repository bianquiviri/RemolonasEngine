{ pkgs, ... }: {
  # Canal de paquetes
  channel = "stable-23.11";

  # Herramientas necesarias en el workspace (mínimas para orquestar Docker)
  packages = [
    pkgs.php83
    pkgs.php83Packages.composer
    pkgs.nodejs_22
    pkgs.docker-compose
  ];

  # Habilitar Docker dentro de Antigravity
  services.docker.enable = true;

  # Variables de entorno iniciales
  env = {
    DB_DATABASE = "remolonas_db";
    DB_USERNAME = "admin";
    DB_PASSWORD = "secret_password";
  };

  # Hooks de ciclo de vida
  idx = {
    extensions = [
      "codingyu.laravel-goto-view"
      "onecentlin.laravel5-snippets"
      "amiralizadeh9480.laravel-extra-intellisense"
    ];

    workspace = {
      # Se ejecuta cuando el workspace se crea por primera vez
      onCreate = {
        docker-setup = "docker-compose build";
        composer-install = "docker-compose exec -T app composer install";
      };
      
      # Se ejecuta cada vez que se abre el workspace
      onStart = {
        run-containers = "docker-compose up -d";
      };
    };
  };
}