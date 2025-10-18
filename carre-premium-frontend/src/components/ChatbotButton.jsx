import React, { useState } from 'react';
import Chatbot from './Chatbot';

const ChatbotButton = () => {
  const [isChatbotOpen, setIsChatbotOpen] = useState(false);

  const handleOpenChat = () => {
    console.log('Chatbot button clicked!');
    setIsChatbotOpen(true);
  };

  return (
    <>
      {/* Floating Chatbot Button */}
      <div className="fixed bottom-6 right-6 z-50">
        <button
          onClick={handleOpenChat}
          className="w-14 h-14 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 flex items-center justify-center group"
          aria-label="Ouvrir le chatbot"
        >
          <i className="fas fa-comments text-xl group-hover:animate-bounce"></i>
        </button>

        {/* Pulse animation ring */}
        <div className="absolute -inset-1 bg-gradient-to-r from-purple-600 to-purple-700 rounded-full animate-ping opacity-20"></div>

        {/* Tooltip */}
        <div className="absolute bottom-full right-0 mb-2 px-3 py-1 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
          Besoin d'aide ?
          <div className="absolute top-full right-3 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
        </div>
      </div>

      {/* Chatbot Modal */}
      <Chatbot
        isOpen={isChatbotOpen}
        onClose={() => setIsChatbotOpen(false)}
      />
    </>
  );
};

export default ChatbotButton;
