import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';

const Chatbot = ({ isOpen, onClose }) => {
  const [messages, setMessages] = useState([]);
  const [inputMessage, setInputMessage] = useState('');
  const [isTyping, setIsTyping] = useState(false);
  const messagesEndRef = useRef(null);
  const navigate = useNavigate();

  // Base de connaissances du chatbot
  const knowledgeBase = {
    greetings: {
      keywords: ['bonjour', 'salut', 'hello', 'hi', 'bonsoir', 'hey'],
      responses: [
        "Bonjour ! 👋 Je suis l'assistant virtuel de Carré Premium. Comment puis-je vous aider aujourd'hui ?",
        "Salut ! 😊 Bienvenue sur Carré Premium. Que puis-je faire pour vous ?",
        "Bonjour ! Ravi de vous aider. Posez-moi vos questions sur nos services !"
      ]
    },
    vols: {
      keywords: ['vol', 'vols', 'avion', 'billet', 'billets', 'flight', 'réserver un vol', 'destination'],
      responses: [
        "✈️ Nous proposons des vols vers toutes les destinations internationales ! Vous pouvez rechercher et réserver vos billets directement sur notre page Vols. Voulez-vous que je vous y redirige ?",
        "Pour réserver un vol, rendez-vous sur notre page Vols où vous pourrez comparer les prix et horaires en temps réel via notre partenariat avec Amadeus. Puis-je vous aider avec une destination spécifique ?"
      ],
      action: () => navigate('/flights')
    },
    evenements: {
      keywords: ['événement', 'événements', 'event', 'concert', 'match', 'spectacle', 'sport', 'tennis', 'football'],
      responses: [
        "🎭 Nous offrons l'accès aux plus grands événements sportifs et culturels ! Consultez notre page Événements pour voir tous les événements disponibles. Souhaitez-vous y accéder ?",
        "Nos événements VIP incluent des matchs de tennis, football, concerts et bien plus ! Visitez notre page Événements pour découvrir toutes nos offres exclusives."
      ],
      action: () => navigate('/events')
    },
    packages: {
      keywords: ['package', 'packages', 'forfait', 'séjour', 'voyage', 'hélicoptère', 'jet privé', 'luxe'],
      responses: [
        "🚁 Découvrez nos packages touristiques de luxe incluant des tours en hélicoptère et jets privés ! Consultez notre page Packages pour voir toutes nos offres. Voulez-vous y accéder ?",
        "Nos packages premium offrent des expériences uniques et inoubliables. Visitez notre page Packages pour découvrir nos offres exclusives !"
      ],
      action: () => navigate('/packages')
    },
    prix: {
      keywords: ['prix', 'coût', 'tarif', 'combien', 'cher', 'payer', 'paiement'],
      responses: [
        "💰 Nos prix varient selon la destination, la date et le type de service. Pour obtenir un devis précis, je vous invite à faire une recherche sur notre site. Nos prix sont compétitifs et transparents !",
        "Les tarifs dépendent de plusieurs facteurs. Utilisez notre moteur de recherche pour voir les prix en temps réel. Nous acceptons plusieurs moyens de paiement sécurisés."
      ]
    },
    reservation: {
      keywords: ['réserver', 'réservation', 'booking', 'commander', 'acheter'],
      responses: [
        "📝 Pour effectuer une réservation, il vous suffit de créer un compte ou de vous connecter, puis de sélectionner le service souhaité. Le processus est simple et sécurisé !",
        "La réservation se fait en quelques clics ! Choisissez votre service, remplissez vos informations et procédez au paiement sécurisé. Besoin d'aide pour une réservation spécifique ?"
      ]
    },
    compte: {
      keywords: ['compte', 'profil', 'inscription', 'connexion', 'login', 'register', 's\'inscrire'],
      responses: [
        "👤 Vous pouvez créer un compte gratuitement pour accéder à des avantages exclusifs : réservations rapides, historique, offres spéciales et plus encore ! Cliquez sur 'Inscription' en haut à droite.",
        "Avec un compte Carré Premium, profitez de réservations simplifiées et d'offres personnalisées. L'inscription est gratuite et rapide !"
      ],
      action: () => navigate('/register')
    },
    contact: {
      keywords: ['contact', 'contacter', 'téléphone', 'email', 'aide', 'support', 'assistance'],
      responses: [
        "📞 Notre équipe est à votre disposition ! Visitez notre page Contact pour nous joindre par téléphone, email ou chat. Nous répondons rapidement à toutes vos questions.",
        "Besoin d'aide ? Contactez-nous via notre page Contact. Notre service client est disponible pour vous assister dans vos démarches !"
      ],
      action: () => navigate('/contact')
    },
    paiement: {
      keywords: ['paiement', 'payer', 'carte', 'mobile money', 'stripe', 'sécurisé'],
      responses: [
        "💳 Nous acceptons plusieurs moyens de paiement sécurisés : cartes bancaires (Visa, Mastercard) et Mobile Money. Toutes les transactions sont cryptées et 100% sécurisées.",
        "Vos paiements sont protégés par les dernières technologies de sécurité. Nous utilisons Stripe pour les cartes bancaires et supportons également Mobile Money."
      ]
    },
    annulation: {
      keywords: ['annuler', 'annulation', 'remboursement', 'modifier', 'changer'],
      responses: [
        "🔄 Les conditions d'annulation varient selon le type de service. Consultez vos réservations dans votre espace personnel ou contactez notre service client pour toute modification.",
        "Pour annuler ou modifier une réservation, rendez-vous dans 'Mes Réservations' ou contactez-nous. Les conditions dépendent du service réservé."
      ]
    },
    horaires: {
      keywords: ['horaire', 'horaires', 'heure', 'quand', 'disponibilité', 'ouvert'],
      responses: [
        "⏰ Notre plateforme est disponible 24h/24 et 7j/7 pour vos réservations en ligne ! Notre service client est joignable du lundi au vendredi de 9h à 18h.",
        "Vous pouvez réserver à tout moment sur notre site. Pour toute question, notre équipe est disponible en semaine de 9h à 18h."
      ]
    },
    merci: {
      keywords: ['merci', 'thanks', 'thank you', 'super', 'parfait', 'génial'],
      responses: [
        "De rien ! 😊 C'est un plaisir de vous aider. N'hésitez pas si vous avez d'autres questions !",
        "Avec plaisir ! Si vous avez besoin d'autre chose, je suis là pour vous aider. Bon voyage avec Carré Premium ! ✈️"
      ]
    },
    default: {
      responses: [
        "Je ne suis pas sûr de comprendre votre question. Pouvez-vous reformuler ? Vous pouvez me demander des informations sur nos vols, événements, packages, réservations ou nous contacter.",
        "Hmm, je n'ai pas bien compris. Je peux vous aider avec : les vols ✈️, les événements 🎭, les packages 🚁, les réservations 📝, ou le contact 📞. Que souhaitez-vous savoir ?"
      ]
    }
  };

  // Questions suggérées
  const suggestedQuestions = [
    "Comment réserver un vol ?",
    "Quels événements proposez-vous ?",
    "Quels sont vos packages luxe ?",
    "Comment vous contacter ?",
    "Quels moyens de paiement acceptez-vous ?"
  ];

  // Initialiser avec un message de bienvenue
  useEffect(() => {
    if (isOpen && messages.length === 0) {
      setMessages([
        {
          type: 'bot',
          text: "Bonjour ! 👋 Je suis l'assistant virtuel de Carré Premium. Comment puis-je vous aider aujourd'hui ?",
          timestamp: new Date()
        }
      ]);
    }
  }, [isOpen]);

  // Auto-scroll vers le bas
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  // Analyser le message et trouver la meilleure réponse
  const getBotResponse = (userMessage) => {
    const lowerMessage = userMessage.toLowerCase();
    
    // Chercher dans la base de connaissances
    for (const [category, data] of Object.entries(knowledgeBase)) {
      if (category === 'default') continue;
      
      const hasKeyword = data.keywords.some(keyword => 
        lowerMessage.includes(keyword.toLowerCase())
      );
      
      if (hasKeyword) {
        const response = data.responses[Math.floor(Math.random() * data.responses.length)];
        return { text: response, action: data.action };
      }
    }
    
    // Réponse par défaut
    const defaultResponse = knowledgeBase.default.responses[
      Math.floor(Math.random() * knowledgeBase.default.responses.length)
    ];
    return { text: defaultResponse, action: null };
  };

  // Envoyer un message
  const handleSendMessage = () => {
    if (!inputMessage.trim()) return;

    // Ajouter le message de l'utilisateur
    const userMsg = {
      type: 'user',
      text: inputMessage,
      timestamp: new Date()
    };
    setMessages(prev => [...prev, userMsg]);
    setInputMessage('');
    setIsTyping(true);

    // Simuler un délai de réponse
    setTimeout(() => {
      const { text, action } = getBotResponse(inputMessage);
      const botMsg = {
        type: 'bot',
        text: text,
        timestamp: new Date(),
        action: action
      };
      setMessages(prev => [...prev, botMsg]);
      setIsTyping(false);
    }, 1000 + Math.random() * 1000); // 1-2 secondes
  };

  // Gérer les questions suggérées
  const handleSuggestedQuestion = (question) => {
    setInputMessage(question);
    setTimeout(() => handleSendMessage(), 100);
  };

  // Gérer l'action (navigation)
  const handleAction = (action) => {
    if (action) {
      action();
      onClose();
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-end justify-end p-4 sm:p-6">
      {/* Overlay */}
      <div 
        className="absolute inset-0 bg-black bg-opacity-30 backdrop-blur-sm"
        onClick={onClose}
      ></div>

      {/* Chatbot Window */}
      <div className="relative w-full max-w-md h-[600px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col animate-slide-up">
        {/* Header */}
        <div className="bg-gradient-to-r from-purple-600 to-purple-700 p-4 rounded-t-2xl flex items-center justify-between">
          <div className="flex items-center space-x-3">
            <div className="w-10 h-10 bg-white rounded-full flex items-center justify-center">
              <i className="fas fa-robot text-purple-600 text-xl"></i>
            </div>
            <div>
              <h3 className="text-white font-bold">Assistant Carré Premium</h3>
              <p className="text-purple-200 text-xs flex items-center">
                <span className="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                En ligne
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors"
          >
            <i className="fas fa-times text-xl"></i>
          </button>
        </div>

        {/* Messages */}
        <div className="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900">
          {messages.map((message, index) => (
            <div
              key={index}
              className={`flex ${message.type === 'user' ? 'justify-end' : 'justify-start'}`}
            >
              <div
                className={`max-w-[80%] rounded-2xl p-3 ${
                  message.type === 'user'
                    ? 'bg-purple-600 text-white rounded-br-none'
                    : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-bl-none shadow-md'
                }`}
              >
                <p className="text-sm whitespace-pre-wrap">{message.text}</p>
                {message.action && (
                  <button
                    onClick={() => handleAction(message.action)}
                    className="mt-2 text-xs bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded-full transition-colors"
                  >
                    Y accéder →
                  </button>
                )}
                <p className="text-xs opacity-60 mt-1">
                  {message.timestamp.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                </p>
              </div>
            </div>
          ))}

          {/* Typing indicator */}
          {isTyping && (
            <div className="flex justify-start">
              <div className="bg-white dark:bg-gray-800 rounded-2xl rounded-bl-none p-3 shadow-md">
                <div className="flex space-x-2">
                  <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                  <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '0.2s' }}></div>
                  <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '0.4s' }}></div>
                </div>
              </div>
            </div>
          )}

          <div ref={messagesEndRef} />
        </div>

        {/* Suggested Questions */}
        {messages.length <= 1 && (
          <div className="px-4 py-2 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <p className="text-xs text-gray-500 dark:text-gray-400 mb-2">Questions fréquentes :</p>
            <div className="flex flex-wrap gap-2">
              {suggestedQuestions.slice(0, 3).map((question, index) => (
                <button
                  key={index}
                  onClick={() => handleSuggestedQuestion(question)}
                  className="text-xs bg-white dark:bg-gray-800 hover:bg-purple-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full border border-gray-200 dark:border-gray-600 transition-colors"
                >
                  {question}
                </button>
              ))}
            </div>
          </div>
        )}

        {/* Input */}
        <div className="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl">
          <div className="flex items-center space-x-2">
            <input
              type="text"
              value={inputMessage}
              onChange={(e) => setInputMessage(e.target.value)}
              onKeyPress={(e) => e.key === 'Enter' && handleSendMessage()}
              placeholder="Tapez votre message..."
              className="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
            />
            <button
              onClick={handleSendMessage}
              disabled={!inputMessage.trim()}
              className="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-full p-2 w-10 h-10 flex items-center justify-center transition-colors"
            >
              <i className="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Chatbot;
