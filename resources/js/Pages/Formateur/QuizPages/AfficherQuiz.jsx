import React, { useState} from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import QuestionCard from '@/Components/QuestionCard'


export default function AfficherQuiz({ quiz, questions}) {

  return (
    <DashboardLayout>
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Questions du quiz : {quiz.title}</h1>
        <div className="space-y-4">
          {questions.map((question, index) => (
            <QuestionCard
                key={question.id}
                question={question}
                index={index}
                totalQuestions={questions.length}
            />
          ))}   
        </div>
    </div>
    </DashboardLayout>
  )
}
