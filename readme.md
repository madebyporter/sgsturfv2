# WordPress Setup Guide

This guide will walk you through the steps to install and set up WordPress on your local development environment.

## Prerequisites

Before you begin, make sure you have the following installed on your machine:

- [MAMP](https://www.mamp.info/) or [XAMPP](https://www.apachefriends.org/index.html)

## Installation

Follow these steps to install WordPress:

1. Start your local development environment (MAMP or XAMPP).
2. Clone this git repo
3. Install Node.js via terminal
4. Install NPM via terminal
5. Go into folder via terminal 'cd /wp-content/themes/sgsturf'
6. In terminal, run 'npm install', which should install the latest packages into the sgsturf theme folder, such as tailwind, scss, etc
7. Run 'npm run start' to make any edits that's involved with Tailwind
8. Go to https://tailwindcss.com/ to learn more about tailwind's system
9. More CSS styles are set within wp-content/sgsturf/assets/scss/, specifically tailwind.scss, where .grid-main is located
10. Push to the remote git repo from the root folder, to include all the files
11. You can grab the wp-config.php file for your local from the production folder

## Troubleshooting

If you encounter any issues during the installation or setup process, refer to the official documentation or community forums for assistance.

## Contributing

Contributions are welcome! If you find any errors or have suggestions for improvement, please submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
