# Environments and Deployment

## Environment Stack

Local Dev → Test / Staging → Production

---

## Source of Truth

### Code + Structure
Git repository.

Includes:
- Plugin code (if necessary)
- Elementor exports
- Documentation
- Assets

---

### Content
Production database + uploads.

---

## Deployment Flow

### Code Promotion
Git → Deploy forward through environments.

---

### Elementor Templates
Export → Commit → Import → Assign.

---

### Content Migration
Rare. Usually created only in Production.

---

## When Full Site Migration Is Allowed

- Initial launch
- Disaster recovery
- Platform rebuild
