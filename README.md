
# Laravel Stack Installer

Laravel Stack Installer is a powerful command-line tool to quickly set up Laravel with different stacks. It simplifies the installation process for developers by automating the setup of popular Laravel configurations.

## Features

- Interactive menu for easy selection of stacks.
- Install Laravel with a variety of pre-configured stacks:
  - ▲   Next.js + Breeze
  - ◯   React + Tailwind Stack
  - ✧   Vue + Inertia.js + Tailwind Stack
  - τ   TALL Stack
  - ◉   Livewire + Tailwind Stack
  - ◯   API-Only Stack
  - ⬡   GraphQL Stack

---

## Installation

Laravel Stack Installer is distributed as a Composer package and can be installed globally.

### Install via Composer
Run the following command to install the tool globally using Composer:
```bash
composer global require ngfw/laravel-stack
```

Make sure the composer path (e.g., `~/.composer/vendor/bin` or `~/.config/composer/vendor/bin`) is added to your system's PATH. 

Or add it with command:

- On Linux or macOS:
  ```bash
  export PATH=$PATH:~/.composer/vendor/bin
  ```
  Add the above line to your shell configuration file (e.g., `~/.bashrc` or `~/.zshrc`).

- On Windows:
  Add the global `composer\vendor\bin` directory to your system's environment variables.

---

## Usage

### Running the Installer
Once installed, you can use the tool directly from the command line. Run the following command to start the interactive menu:
```bash
laravel-stack
```

To skip the menu and run a specific installer directly, use the following command format:
```bash
laravel-stack \n
    --project="<YOUR_PROJECT_NAME>" \n
    --db.host="<DB_HOST>" \n
    --db.user="<DB_USER>" \n
    --db.password="<DB_PASSWORD>"
```
For example:
```bash
laravel-stack --project="MY_FIRST_STACK" --db.host="127.0.0.1" --db.user="root" --db.password="<YOUR_PASSWORD>"
```


### **IMPORTANT!**
**_Installer will create new MySQL database and name it your project name._**

---

## Available Installers
The following installers are available in Laravel Stack Installer. Each provides a tailored setup for a specific development stack.

| Installer                      | Description                                                                 |
|--------------------------------|-----------------------------------------------------------------------------|
| **Next.js + Breeze**           | Next.js + Laravel API + Breeze: A seamless setup for modern web apps using Next.js as the frontend and Laravel as the backend. |
| **React + Tailwind Stack**     | React + Tailwind CSS: A stack for building modern frontend interfaces with React and styling with Tailwind CSS. |
| **TALL Stack**  | TALL Stack: Combines TailwindCSS, AlpineJS, Laravel, and FilamentPHP for a clean and productive UI/UX workflow. |
| **Livewire + Tailwind Stack**  | Livewire + Tailwind CSS: A powerful stack for dynamic and real-time UIs with Livewire and Tailwind CSS. |
| **API-Only Stack**             | API-Only Stack: A lightweight Laravel setup for purely API-driven applications.|
| **GraphQL Stack**              | GraphQL Stack: Integrates GraphQL with Laravel for flexible API queries.|

---

## Development Progress
Below is the current status of all supported installers:

- [x] **Next.js + Breeze**
- [x] **React + Tailwind Stack**
- [x] **Vue + Inertia.js + Tailwind Stack**
- [x] **TALL Stack**
- [ ] **Livewire + Tailwind Stack**
- [ ] **API-Only Stack**
- [ ] **GraphQL Stack**

---

## Contributing
Contributions are welcome! If you’d like to add new stacks or features, please submit a pull request or open an issue on the repository.

---

## License
This project is licensed under the MIT License. See the `LICENSE` file for details.

