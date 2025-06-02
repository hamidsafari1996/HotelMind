import time
import logging
import requests

logger = logging.getLogger("hotelai")

def wait_for_ollama(base_url="http://ollama:11434", timeout=120):
    start_time = time.time()
    while time.time() - start_time < timeout:
        try:
            res = requests.get(f"{base_url}/api/tags", timeout=5)
            if res.status_code == 200:
                logger.info("Ollama is ready!")
                return True
        except Exception as e:
            logger.info(f"Waiting for Ollama... ({e})")
        time.sleep(5)
    return False