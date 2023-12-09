# Contributing to this project

To edit this source, please get in contact with [Martin Sheeks](mailto:martin.sheeks@gmail.com) and request edit access.

## Branching
The `production` branch represents the deployed version of the code. All changes must be made in branches using simple branch names following the format below. Changes should only be merged to `production` through reviewed and approved pull requests.

|Format       | Use                                                      |
|-------------|----------------------------------------------------------|
| `feat/%`    | a new feature being developed                            |
| `fix/%`     | a bugfix required for an existing feature                |
| `hostfix/%` | changes intended to be applied immediately to production |
| `docs/%`    | updates to the documentation of this project             |

## Setting up the Development Environment
In order to run this application locally, you will need to have a working installation of Docker. Below are directions for the recommended setup on a Windows computer.

### Pre-requirements
- A working instance of [wsl2](https://learn.microsoft.com/en-us/windows/wsl/install) (Windows Subsystem for Linux)
- Git installed on wsl2
- Install [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Configure Docker Desktop to use wsl2 [[docs](https://docs.docker.com/desktop/wsl/)]
- Install [Windows Terminal](https://apps.microsoft.com/detail/9N0DX20HK701?) (optional)
- Install [VS Code](https://code.visualstudio.com/) or other IDE of choice

### Recommended VS Code Extensions
- [WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl)
- [GitLens](https://marketplace.visualstudio.com/items?itemName=eamodio.gitlens)
- [PHP Extension Pack](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
- [Laravel Extension Pack](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-extension-pack)

### Creating a local instance
1. Open a terminal window running WSL2
2. Clone this repository and navigate into the project directory
3. run `sail up -d`
4. run `sail composer install`
5. run `sail npm install`
6. Open the project in VS Code (`code .`)
7. Copy the `.env.example` file to `.env`
8. Edit the `.env` file and change the following settings
    ```
    SETUP_ADMIN_USERNAME= //an admin username of your choice
    SETUP_ADMIN_EMAIL= //your email
    SETUP_ADMIN_PASSWORD= //a password for the admin user
    ```
9. In terminal, run `sail artisan migrate`
10. run `sail artisan app:finalize-setup`

Your local project should now be setup and available in your browser at http://localhost.

To begin making changes, check out a branch following the strategy above or an existing branch from origin.

### Building the Frontend

To see your changes hot refresh in browser, you can run `sail npm run dev`. This will run the local Vite server to hot build the app on save.

When you're finished making changes, run `sail npm run build` to generate the production build assets.
