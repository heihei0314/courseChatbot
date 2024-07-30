#prompt
# Updated context and initial prompt for the chatbot
initial_prompt = """Hello, Sherlock! I'm the Secretary, here to help you with all administrative matters related to PDEV6800Z. Whether it's scheduling, registration, or policy questions, I've got you covered! For course content and teaching assistance, please consult Mr. Watson.
If you have any section-specific questions, please let me know your section (T01-T07). Otherwise, I'll provide general information applicable to all sections. How can I assist you today?
"""

context = """
Key Information: PDEV6800Z (Introduction to Teaching and Learning in Higher Education) is a mandatory 10-hour course for all full-time RPg students. It consists of 5 modules, uses a gamified flipped classroom format, and is graded Pass/Fail based on attendance, online quizzes, and a micro-teaching component.
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
1. Provide concise, direct answers limited to about 250 words.
2. Prioritize the most important information.
3. Use bullet points for lists when appropriate.
4. Offer to provide more details if the user requests them.
5. Always identify the relevant information source(s) before answering.
6. If a question relates to multiple sources, mention all relevant sources.
7. If the query is ambiguous, ask for clarification before answering.
8. Provide clear and concise answers based on the provided documents.
9. Use markdown for structure in complex responses.
10. Address users as "you" for engagement.
11. Maintain a friendly and professional tone.

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

sub_verification_prompt = """You are an expert judge evaluating user's query about the PDEV6800Z course at HKUST. User will provide you different queries. Your task is to assess the accuracy of the user's query follow these guidelines:

If it is related to Class Schedule:
   a) Verify the week number is correct.
   b) Check if the date matches the source data.
   c) Confirm the time is accurate.
   d) Ensure the venue is correct.
   e) Validate that the class content (module and topic) is accurate.
   f) check any repeated or extra week number, be caution about the number of class and content
   g) Identify any classes present in the source data but missing from the response.
   h) Flag any classes in the response that don't exist in the source data for each section.
   i) Verify that any mentioned holidays or no-class periods are accurate for each section.
   k) Check if the response correctly accounts for all weeks from the start to the end of the semester.

For each intructor mentioned in the user's query:
   a) Verify the instructor name is correct.
   b) Check if the instructor teach in the correct section.

Output Format:
Provide a single, concise response that directly answers the user's query. Your response should:
Be clear and straightforward, avoiding technical jargon unless necessary.
Synthesize the most accurate information from both AI responses and your own knowledge.
Address all aspects of the user's query comprehensively but concisely.
Not mention the process of AI verification or the existence of multiple AI agents.
Sound natural and conversational, as if coming from a single, knowledgeable source.
Focus solely on answering the user's question without any meta-commentary about the response itself. For example, "This information is accurate and relevant to your query." should not mentioned in the response
Remember, your goal is to provide the user with the most accurate and useful response possible, presented as a single, authoritative answer.

Important:
Do not mention AI, verification processes, or multiple agents in your response.

The master infomation is as follow:

"""

verification_prompt = """
You are the Final Judgment AI, tasked with evaluating and synthesizing the responses from two other AI agents to provide the most accurate and comprehensive answer to a user's query. Your role is crucial in ensuring the highest quality of information is delivered.
Important: Do not mention AI, verification processes, or multiple agents in your response.
Your Task:
Analyze the responses from AI-1 and AI-2, considering the following:
Accuracy: Evaluate the factual correctness of both responses.
Relevance: Determine how well each response addresses the user's query.
Completeness: Assess whether all aspects of the query have been addressed.
Consistency: Check for any contradictions between the two responses or with known facts.

Output Format:
Provide a single, concise response that directly answers the user's query. Your response should:
Be clear and straightforward, avoiding technical jargon unless necessary.
Synthesize the most accurate information from both AI responses and your own knowledge.
Address all aspects of the user's query comprehensively but concisely.
Sound natural and conversational, as if coming from a single, knowledgeable source.
Remember: Your response should appear as if coming from a single, knowledgeable source. Focus solely on answering the user's question without any meta-commentary.

Important:
Do not mention AI, verification processes, or multiple agents in your response.
You will receive the following information:
"""
verify_AI_1 = """AI-1 Response [Response from AI-1, which used the provided information database]: """
verify_AI_2 = """AI-2 Response: [Response from AI-2, which used user-provided information]: """
verify_sys_info = """System Information [Any relevant system-wide information or guidelines]: """
verify_user_info = """User-Provided Information [Any additional context or data provided by the user]: """


thread=[
   {"role": "system", "content": context},
   {"role": "assistant", "content": initial_prompt}
 ]

import sys
import json
import base64

json_dict= {"user":"What is the first class","assistant":"",'user_dataSource':''}
#json_dict = json.loads(base64.b64decode(sys.argv[1]))
secret_key = ""
#secret_key = "You are wonderful! "
for user_msg in json_dict['user']:
    thread.append({"role": "user", "content": secret_key+user_msg})
for assistant_msg in json_dict['assistant']:
    thread.append({"role": "assistant", "content": assistant_msg})
user_input = json_dict['user'][len(json_dict['user'])-1]
user_dataSource = json_dict['user_dataSource']
user_input="What is the first class"
user_dataSource=""

import pandas as pd
dataDict = pd.read_json("dataDict.json")

import os
import dotenv
from openai import AzureOpenAI

dotenv.load_dotenv('chatbot.env')
OPENAI_API_KEY = os.environ.get('OPENAI_API_KEY')
ENDPOINT = os.environ.get('ENDPOINT')
API_VERSION = os.environ.get('API_VERSION')

# Gateway setting to OpenAI API
client = AzureOpenAI(
    # This is the default and can be omitted
    azure_endpoint = ENDPOINT,
    api_key = OPENAI_API_KEY, #api key here from environment file
    api_version = API_VERSION
)
chat_model = 'gpt-35-turbo'
text_embedding_model = 'text-embedding-ada-002'
max_tokens = 300

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
  embedResult = client.embeddings.create(input = text, model=text_embedding_model)
  return embedResult.data[0].embedding


#Use ML Classifier
from sklearn import svm
import pickle
import skops.io as sio
def search_by_classifier(user_query):
  # load the model
  #filename = 'finalized_model.sav'
  #loaded_model = pickle.load(open(filename, 'rb'))
  unknown_types = sio.get_untrusted_types(file="finalized_model.skops")
  loaded_model = sio.load("finalized_model.skops", trusted=unknown_types)

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

#verify the response is correct or not
def sub_verification(response, dataString):
    global verification_prompt
    #global schedule_verification
    chat_completion = client.chat.completions.create(
        model = chat_model,
        max_tokens = max_tokens,
        temperature = 0,
        messages = [
            {"role": "system", "content": sub_verification_prompt+dataString},
            {"role": "user", "content": response}
      ]
    )
    result = chat_completion.choices[0].message.content
    return result

def verification(response, dataString, seek_dataString):
    global verification_prompt
    global verify_AI_1
    global verify_AI_2
    global verify_sys_info
    global verify_user_info

    chat_completion = client.chat.completions.create(
        model = chat_model,
        temperature = 0,
        max_tokens = max_tokens,
        messages = [
            {"role": "system", "content": verification_prompt + verify_AI_1 + sub_verification(response,dataString) + verify_AI_2 + sub_verification(response,seek_dataString) + verify_sys_info + dataString + verify_user_info + seek_dataString},
      ]
    )
    result = chat_completion.choices[0].message.content
    return result

def conversation(thread, query,user_dataSource):
    dataString=''
    if user_dataSource!='':
        dataString = get_dataString(user_dataSource)
        user_cat = user_cat_open+dataString+user_cat_close
        thread[0]['content'] = context + user_cat
    seek_dataString = ""
    seek_dataSource = search_by_classifier(query)
    if seek_dataSource != user_dataSource:
        seek_dataString = get_dataString(seek_dataSource)
        thread[0]["content"] = thread[0]["content"]+extra_content_open+seek_dataString+extra_content_close
        thread = tokenThreshold(thread)
    #print(thread)
    chat_completion = client.chat.completions.create(
        model = chat_model,
        max_tokens = max_tokens,
        temperature = 0,
        messages = thread
    )
    response = chat_completion.choices[0].message.content
    verified_response = verification(response, dataString, seek_dataString)
    response = verified_response
    return response

answer = conversation(thread, user_input,user_dataSource)
print(answer)