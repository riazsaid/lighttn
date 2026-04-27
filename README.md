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
