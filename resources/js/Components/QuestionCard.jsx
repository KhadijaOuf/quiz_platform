import React from 'react'
import { ChevronUpIcon, ChevronDownIcon, XMarkIcon } from '@heroicons/react/24/solid'

export default function QuestionCard({ question, index, totalQuestions, onMoveUp, onMoveDown, onDelete, showActions = false }) {
  const renderReponses = () => {
    if (question.type === 'vrai_faux') {
      return (
        <div className="flex gap-4 mt-2">
          <div className="flex items-center">
            <input type="radio" disabled checked={question.reponse_attendues?.[0]?.texte === 'Vrai'} className="mr-2" />
            <span>Vrai</span>
          </div>
          <div className="flex items-center">
            <input type="radio" disabled checked={question.reponse_attendues?.[0]?.texte === 'Faux'} className="mr-2" />
            <span>Faux</span>
          </div>
        </div>
      )
    }

    if (question.type === 'multiple' || question.type === 'single') {
      return (
        <div className="mt-2">
          {question.reponse_attendues?.map((rep, idx) => (
            <div key={idx} className="flex items-center mb-1">
              <input
                type={question.type === 'multiple' ? 'checkbox' : 'radio'}
                disabled
                checked={rep.est_correct}
                className="mr-2"
              />
              <span className="mr-2">{rep.texte}</span>
              {question.type === 'multiple' && rep.note_partielle > 0 && (
                <span className="text-sm text-gray-500">
                  ({rep.note_partielle} pts)
                </span>
              )}
            </div>
          ))}
        </div>
      )
    }

    if (question.type === 'reponse_courte') {
      return (
        <div className="mt-2 italic text-gray-600">
          Réponse attendue : {question.reponse_attendues?.map((r) => r.texte).join(', ')}
        </div>
      )
    }

    return null
  }

  return (
    <div className="border p-4 rounded-xl shadow mb-4 bg-white relative">
      <div className="flex flex-col h-full">
        <div>
          <div className="flex justify-between items-center mb-2">
            <h3 className="text-lg font-semibold">Question {index + 1}</h3>
            {showActions && (
              <div className="flex flex-row gap-1 items-center">
                {index > 0 && (
                  <button
                    onClick={() => onMoveUp(index)}
                    className="p-1 rounded hover:bg-gray-200"
                    title="Monter"
                  >
                    <ChevronUpIcon className="w-5 h-5" />
                  </button>
                )}
                {index < totalQuestions - 1 && (
                  <button
                    onClick={() => onMoveDown(index)}
                    className="p-1 rounded hover:bg-gray-200"
                    title="Descendre"
                  >
                    <ChevronDownIcon className="w-5 h-5" />
                  </button>
                )}
                <button
                  onClick={() => onDelete(index)}
                  className="p-1 rounded hover:bg-red-100 text-red-600"
                  title="Supprimer"
                >
                  <XMarkIcon className="w-5 h-5" />
                </button>
              </div>
            )}
          </div>
          <p className="mt-1 text-gray-700">{question.enonce}</p>
          {renderReponses()}
        </div>
        <p className="text-sm text-gray-500 mt-auto self-end mr-3">
          Note : {question.note}
        </p>
      </div>
    </div>
  )
}
