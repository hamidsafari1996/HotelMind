export default function getRatingText(score) {
    if (score >= 9) {
      return "Ausgezeichnet";
    } else if (score >= 8.6) {
      return "Fabelhaft";
    } else if (score >= 8.5) {
      return "Sehr gut";
    } else if (score >= 5) {
      return "Gut";
    } else if (score >= 3) {
      return "Ausreichend";
    } else {
      return "Schlecht";
    }
  }
  