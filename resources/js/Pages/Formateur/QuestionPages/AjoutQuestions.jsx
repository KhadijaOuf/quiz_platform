import React, { useState} from 'react'
import { router } from '@inertiajs/react'
import ModaleQuestion from '@/Components/ModaleQuestion'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import QuestionCard from '@/Components/QuestionCard'
import axios from 'axios'


export default function AjoutQuestions({ quiz, questions: initialQuestions }) {
  const [questions, setQuestions] = useState(initialQuestions)
  const [showModal, setShowModal] = useState(false)

  function activerQuiz(id) {
    if (confirm("Confirmer l'activation de ce quiz ? Une fois actif, il ne sera plus modifiable.")) {
      axios.put(`/formateur/quizzes/${id}/activer`)
        .then(() => {
          router.visit('/formateur/quizzes')  // ← redirection manuelle après succès
        })
        .catch(error => {
          alert(error.response?.data?.message || "Erreur lors de l'activation")
        })
    }
  }

  // utiliser Axios pour ajout/suppression sans rechargement
  const ajouterQuestion = async (question) => {
    try {
      const response = await axios.post(`/formateur/quizzes/${quiz.id}/questions`, question)
      const newQuestion = response.data

      setQuestions((prev) => [...prev, newQuestion])
      setShowModal(false)
    } catch (error) {
      console.error("Erreur enregistrement", error)
      alert(error.response?.data?.message || "Échec de l'enregistrement.")
    }
  }

  const handleDelete = async (id) => {
    try {
      await axios.delete(`/formateur/quizzes/${quiz.id}/questions/${id}`)
      setQuestions((prev) => prev.filter((q) => q.id !== id))
    } catch (error) {
      console.error("Erreur suppression", error)
      alert("Échec de la suppression.")
    }
  }

  const handleMoveUp = (index) => {
    if (index === 0) return
    const newQuestions = [...questions]
    ;[newQuestions[index - 1], newQuestions[index]] = [newQuestions[index], newQuestions[index - 1]]
    setQuestions(newQuestions)
  }

  const handleMoveDown = (index) => {
    if (index === questions.length - 1) return
    const newQuestions = [...questions]
    ;[newQuestions[index], newQuestions[index + 1]] = [newQuestions[index + 1], newQuestions[index]]
    setQuestions(newQuestions)
  }

  return (
    <DashboardLayout>
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Questions du quiz : {quiz.title}</h1>
      {questions.length === 0 ? (
        <div className="text-center mt-10">
	        <p>Aucune question pour le moment.</p>
	        <button
	          onClick={() => setShowModal(true)}
	          className="mt-2 px-4 py-2 bg-orange-600 text-white rounded"
	          >
	          Ajouter une question
	        </button>
	      </div>
      ) : (
        <div className="space-y-4">
          <div className="mb-4 text-right">
            {!quiz.est_actif && (
             <button
              onClick={() => setShowModal(true)}
              className="px-4 py-2 bg-orange-600 text-white rounded"
            >
              Ajouter une question
            </button>
          )}
          </div>
          {questions.map((question, index) => (
            <QuestionCard
              key={question.id}
              question={question}
              index={index}
              totalQuestions={questions.length}
              onMoveUp={handleMoveUp}
              onMoveDown={handleMoveDown}
              onDelete={() => handleDelete(question.id)} 
              showActions={true}
            />
          ))}
          {/* Afficher le bouton "Activer" une seule fois si le quiz n'est pas actif */}
          {!quiz.est_actif && (
            <button
              onClick={() => activerQuiz(quiz.id)}
              className="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700"
            >
              Activer le Quiz
            </button>
          )}
            
        </div>
      )}

      {showModal && <ModaleQuestion onClose={() => setShowModal(false)} onSave={ajouterQuestion} />}
    </div>
    </DashboardLayout>
  )
}
