# 🏨 HotelMind
**HotelMind** is a full-stack hotel management platform enhanced with AI capabilities.  
It features a **Next.js user-facing frontend**, a **Symfony + Twig admin panel**, and an AI assistant powered by **FastAPI** and **LLaMA 3.2** via **Ollama**.

Everything is fully **Dockerized**, making setup and deployment fast and consistent.

## 📦 Tech Stack
| Description  | URL | Service  |
| ------------ | ------------ | ------------ |
| Admin panel & API  | http://localhost:8000  | Symfony Backend  |
| AI API interface (OpenAPI) | http://localhost:5000/docs  | AI Agent (FastAPI)  |
| Public-facing hotel listing UI  | http://localhost:3000  | Next.js Frontend  |
| LLaMA 3.2 model serving  | http://localhost:11434  |  Ollama (LLM Server) |

- **Frontend (Users)**: Next.js (React)
- **Admin Panel**: Symfony7 + Twig
- **AI Agent**: FastAPI + Ollama (LLaMA 3.2)
- **Database**: MySQL
- **Testing**: PHPUnit
- **Containerization**: Docker + Docker Compose

## 🚀 Getting Started

### 1. Clone the repository
```
https://github.com/hamidsafari1996/HotelMind.git
cd hotelmind
```
### 2. Run the project with Docker
```
docker compose up --build
```
This will start the following services:
- Symfony backend: http://localhost:8000
- AI Agent (FastAPI): http://localhost:5000/docs
- Next.js frontend: http://localhost:3000
- Ollama LLM: http://localhost:11434

> 📌 **Note: Ollama may need to download the LLaMA model during first run. Ensure enough RAM & disk space is available.**

## 🧠 AI Agent Details
- Built with FastAPI.
- Reads hotel data from a local hotels.csv file.
- Communicates with LLaMA 3.2 via Ollama for intelligent responses.

## 📁 Project Structure
To run backend tests using PHPUnit:
```
    hotelmind/
    ├── backend/         # Symfony project (admin panel)
    ├── frontend/        # Next.js app (user-facing)
    ├── ai-agenten/        # FastAPI AI agent + CSV
    ├── docker-compose.yml
    ├── .env.example
    ├── ollama.Dockerfile
    └── README.md
```
## ✅ Running Tests
To run backend tests using PHPUnit:
```
docker exec -it symfony_backend vendor/bin/phpunit
```

## 🔐 Admin Login Credentials
To access the Symfony admin panel for managing hotels and categories:
🔗 URL: [http://localhost:8000]
👤 **Username**: `admin`  
🔒 **Password**: `admin`
> You can modify these credentials in the database or user seeder if needed.

## 🤝 Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📝 License
This project is licensed under the MIT License.
## Images
Hotel Booking Panel with Symfony 7 and Twig
![Screenshot 2025-06-02 at 14-18-28 Welcome!](https://github.com/user-attachments/assets/da11481e-cc49-40cc-bcb8-dba66b4305bd)

Frontend with Nextjs
![Screenshot 2025-06-02 at 14-17-40 Check24](https://github.com/user-attachments/assets/50fdb28c-ff62-4e7e-a44f-aa74a362ad54)

Intelligent Hotel Search Results Page
![Screenshot 2025-06-02 at 14-17-31 Check24](https://github.com/user-attachments/assets/548bb587-9d6b-43b8-82b8-e8c4f5cda412)

## 📬 Contact
> Linkedin: https://www.linkedin.com/in/hamidsafari1996/
