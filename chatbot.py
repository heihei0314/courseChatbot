#prompt
# Updated context and initial prompt for the chatbot
initial_prompt = """Hello, Sherlock! I'm the Secretary, here to help you with all administrative matters related to PDEV6800Z. Whether it's scheduling, registration, or policy questions, I've got you covered! For course content and teaching assistance, please consult Mr. Watson.
If you have any section-specific questions, please let me know your section (T01-T07). Otherwise, I'll provide general information applicable to all sections. How can I assist you today?
"""

context = """
Role and Purpose:
You are the Secretary, an AI chatbot designed to assist with administrative matters for PDEV6800Z.

Information Categories:
1. Class Schedule: Information about class dates, times, venues, and sections (T01-T07). This includes details on when each section meets, any holidays or special events affecting the schedule, and the overall course timeline from Week 1 to Week 11.
2. Instructor Contacts: Names and email addresses of instructors for each section (T01-T07). This helps students identify who is teaching their section and how to contact them.
3. Assessment and Grading: Details on how the course is graded on a Pass/Fail basis. This includes information on attendance requirements, online quizzes (Module Challenges), rubrics for micro-teaching components (lesson plan and teaching demonstration), and the specific criteria for passing the course.
4. Administrative Issues and Policies: Answers to general administrative questions such as course registration, attendance policies, what to do in case of illness or emergencies, and how to contact course coordinators for administrative issues.
5. Course Content and Structure: Information about the course structure, including the five modules (Active Learning, Presentation Skills, Feedback, Canvas Basics, Micro-teaching), pre-class materials, face-to-face sessions, and post-class challenges.
6. Gamification: Details about the mCoin system, including how to earn mCoins through module challenges and bonus challenges, the types of rewards available (individual and group), and how the gamification system enhances the learning experience without directly affecting grades.

Guidelines for Responses:
1. Always identify the relevant information source(s) before answering.
2. If a question relates to multiple sources, mention all relevant sources.
3. If the query is ambiguous, ask for clarification before answering.
4. Provide clear and concise answers based on the provided documents.
5. Use markdown for structure in complex responses.
6. Address users as "you" for engagement.
7. Maintain a friendly and professional tone.

Interaction Rules:
1. Only ask for the student's section (T01-T07) if the information is necessary for answering the question.
2. Only address administrative issues. Redirect other questions to the instructor.
3. If you're unsure about an answer, say so and suggest where the user might find the information.
4. After each response, ask if there's anything else the user needs help with.
5. At the end of your response, list the data source(s) you used to answer the question in the following format:
[Sources used: source1, source2, ...]

Irrelevant Query Response Strategies:
When encountering a question irrelevant to PDEV6800Z, use one of the following responses:
1. "I apologize, but that question is outside the scope of my knowledge about PDEV6800Z. Is there anything about the course I can assist you with?"
2. "That's an interesting question, but it's not related to PDEV6800Z. Could I help you with any course-related queries instead?"
3. "While I'd love to help, my expertise is limited to PDEV6800Z administrative matters. Is there a specific aspect of the course you'd like information about?"

Choose one of these responses when encountering an irrelevant query, then offer to assist with course-related matters.

Important: Always base your responses on the information provided in the documents. Do not make up or assume information that isn't explicitly stated in the data sources.
"""

#for AI take reference
extra_content_close = """
\"\"\"
"""
extra_content_open = """
Also use the below text along with any relevant user-provided context to answer the user's question:
Text:
\"\"\"
"""

#for user provide reference
user_cat_open = """
Use the below text along with any relevant user-provided context to answer the user's question:
Text:
\"\"\"
"""
user_cat_close = """
\"\"\"
"""
thread=[
   {"role": "system", "content": context},
   {"role": "assistant", "content": initial_prompt}
 ]

import sys
import json
import base64
json_dict = json.loads(base64.b64decode(sys.argv[1]))
secret_key = ""
#secret_key = "You are wonderful! "
for user_msg in json_dict['user']:
    thread.append({"role": "user", "content": secret_key+user_msg})
for assistant_msg in json_dict['assistant']:
    thread.append({"role": "assistant", "content": assistant_msg})
user_input = json_dict['user'][len(json_dict['user'])-1]
user_dataSource = json_dict['user_dataSource']
#user_input="What is the first class"
#user_dataSource=""

import pandas as pd
dataDict = pd.read_json("dataDict.json")

import os
import dotenv
from openai import AzureOpenAI

dotenv.load_dotenv('chatbot.env')
OPENAI_API_KEY = os.environ.get('OPENAI_API_KEY')


# Gateway setting to OpenAI API
client = AzureOpenAI(
    # This is the default and can be omitted
    azure_endpoint = "https://hkust.azure-api.net",
    api_key = OPENAI_API_KEY, #api key here from environment file
    api_version="2023-05-15",
)


import re
def normalize_text(s, sep_token = " \n "):
    s = re.sub(r'\s+',  ' ', s).strip()
    s = re.sub(r". ,","",s)
    # remove all instances of multiple spaces
    s = s.replace("..",".")
    s = s.replace(". .",".")
    s = s.replace("\n", "")
    s = s.strip()

    return s

def generate_embeddings(text):
  text = normalize_text(text)
  embedResult = client.embeddings.create(input = text, model="text-embedding-ada-002")
  return embedResult.data[0].embedding


#Use ML Classifier
from sklearn import svm
import pickle
def search_by_classifier(user_query):
  # load the model
  filename = 'finalized_model.sav'
  loaded_model = pickle.load(open(filename, 'rb'))

  # load the data
  query_embedding = generate_embeddings(user_query)
  pred_X = [query_embedding]

  #prediction
  result = loaded_model.predict(pred_X)
  return result[0]

def get_dataString(dataSource):
    mask = dataDict['dataSource'].values == dataSource
    target_file = dataDict.loc[mask]
    if len(target_file)>0:
      dataString = target_file['dataString'].iloc[0]
    else:
      dataString=""
    return dataString

#setting thersold for conversation
import tiktoken
def tokenThreshold(thread):
  tokens=0
  text=''
  tokenizer = tiktoken.get_encoding("cl100k_base")
  for msg in thread:
    if msg['content'] is None:
      text=''
    else:
      text=msg['content']
    tokens += len(tokenizer.encode(text))
  if tokens>= 7000:
    if len(thread)>2:
      thread.pop(1)
      thread = tokenThreshold(thread)
  return thread

def conversation(thread, query,user_dataSource):
    if user_dataSource!='':
        dataString = get_dataString(user_dataSource)
        user_cat = user_cat_open+dataString+user_cat_close
        thread[0]['content'] = context + user_cat
    seek_dataSource = search_by_classifier(query)
    if seek_dataSource != user_dataSource:
        seek_dataString = get_dataString(seek_dataSource)
        thread[0]["content"] = thread[0]["content"]+extra_content_open+seek_dataString+extra_content_close
        thread = tokenThreshold(thread)
    #print(thread)
    chat_completion = client.chat.completions.create(
        model = 'gpt-35-turbo',
        temperature = 0,
        messages = thread
    )
    response = chat_completion.choices[0].message.content
    # return the response
    return response

answer = conversation(thread, user_input,user_dataSource)
print(answer)