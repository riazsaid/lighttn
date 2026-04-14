## LightTN WordPress Site

This folder is the standalone WordPress project for **LightTN**, initialized to mirror the working local setup used in `cpl`.

It includes:

- A `public/` WordPress docroot
- The shared `atomic-design` custom theme as the starter theme base
- A DDEV config at `.ddev/config.yaml`
- A Git repository initialized locally for this site

### Local Development

Run the project with DDEV once Docker is available:

```bash
ddev start
ddev launch
```

If the database is brand new, finish the install in either of these ways:

```bash
ddev launch /wp-admin/install.php
```

or

```bash
ddev wp core install --url='https://lighttn.ddev.site' --title='LightTN' --admin_user='admin' --admin_password='admin' --admin_email='you@example.com'
```

### Project Notes

- `public/wp-content/themes/atomic-design/` is the main custom theme starter copied from `cpl`.
- `public/wp-config.php` is ready for both DDEV and environment-based non-local configuration.
- `.env.example` contains optional variables for live/non-DDEV environments.

### Git

The repository is initialized locally on the `main` branch. Add the remote when the GitHub repository for LightTN is ready:

```bash
git remote add origin <your-lighttn-repo-url>
```
