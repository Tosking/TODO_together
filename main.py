# -*- coding: utf-8 -*-
import os
from telegram.ext import Updater, Filters, CommandHandler, MessageHandler, CallbackQueryHandler
from telegram import InlineKeyboardButton, InlineKeyboardMarkup
from os import listdir
import json
import logging
import telegram

updater = Updater(token=open("token.txt", "r").read(), use_context=True)
disp = updater.dispatcher

logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', level=logging.INFO
)
logger = logging.getLogger(__name__)

SEP = "\n---------------------------------------------------\n"

get_user = lambda user_id: json.load(open("users/" + str(user_id) + ".json", "r"))
save_user = lambda user, user_id: json.dump(user, open("users/" + str(user_id) + ".json", "w"))
get_list = lambda list_id: json.load(open("lists/" + str(list_id) + ".json", "r"))
save_list = lambda listt, list_id: json.dump(listt, open("lists/" + str(list_id) + ".json", "w"))

keyboards = {"start": [
    [InlineKeyboardButton("Start", callback_data="start")]
    ],
    "menu": [
        [InlineKeyboardButton("Open list", callback_data="open_list")],
    [
        InlineKeyboardButton("Create list", callback_data="create_list"),
        InlineKeyboardButton("Delete list", callback_data="delete_list")
    ],
    [
        InlineKeyboardButton("Join list", callback_data="join_list")
    ],
    [
        InlineKeyboardButton("Change name", callback_data="change_name"),
    ]
    ],
    "list": [
        [InlineKeyboardButton("Back to menu", callback_data="menu")]
    ],
}

#/start command
def start(update, context):
    if str(update.effective_chat.id)+".json" not in listdir("users"):
        create_new_user(update)
        context.bot.send_message(chat_id=update.effective_chat.id, text="Welcome to TODO bot",
                                 reply_markup=InlineKeyboardMarkup(keyboards["start"]))
    else:
        mode = json.load(open("cfg.json", "r"))
        mode[str(update.effective_chat.id)] = ""
        json.dump(mode, open("users/mode.json", "w"))
        menu(update, context)

#Creates main menu
def menu(update, context):
    file = get_user(update.effective_chat.id)
    context.bot.send_message(chat_id=update.effective_chat.id, text="\t\t<b><u>Main menu</u></b>\n""Lists: " + str(len(file["lists_id"])) +
                                 "\nName: " + file["name"] +
                                 "\nID: " + str(update.effective_chat.id),
                                 reply_markup=InlineKeyboardMarkup(keyboards["menu"]), parse_mode=telegram.ParseMode.HTML)

#Creates new user
def create_new_user(update):
    json.dump({"user_id": update.effective_chat.id,
               "name": "",
               "lists_id": [],
               }, open("users/"+str(update.effective_chat.id)+".json", "w"))

#Opens {id} list in editor mode
def edit_list(update, context, id):
    listt = get_list(id)
    buffer = SEP + "\t\t❗Editor mode❗" + SEP
    keyboard = [
        [InlineKeyboardButton("Add new item", callback_data="new_item" + str(listt["id"]))],
        [InlineKeyboardButton("Edit existing item", callback_data="edit_item0" + str(listt["id"]))],
        [InlineKeyboardButton("Delete existing item", callback_data="delete_item0" + str(listt["id"]))],
        [InlineKeyboardButton("Complete item", callback_data="complete_item0" + str(listt["id"]))],
        [InlineKeyboardButton("Rename list", callback_data="rename_list" + str(listt["id"]))],
        [InlineKeyboardButton("Back", callback_data="!" + str(listt["id"]))]
    ]
    if len(listt["items"]) != 0:
        for i in listt["items"]:
            buffer += ("\n• " + str(i))
    else:
        buffer += "\n Nothing there"
    context.bot.send_message(text="\t" + listt["name"] + buffer, reply_markup=InlineKeyboardMarkup(keyboard),
                             chat_id=update.effective_chat.id, parse_mode=telegram.ParseMode.HTML)

#Reacts to messages
def get_message(update, context):
    mode = json.load(open("users/mode.json", "r"))
    cfg = json.load(open("cfg.json", "r"))
    file = get_user(update.effective_chat.id)
    if file["name"] == "":
        file["name"] = update.message.text
        save_user(file, update.effective_chat.id)
        menu(update, context)

    elif mode[str(update.effective_chat.id)].startswith("new_item"):
        listt = get_list(mode[str(update.effective_chat.id)][8::])
        listt["items"].append(update.message.text)
        save_list(listt, mode[str(update.effective_chat.id)][8::])
        edit_list(update, context, mode[str(update.effective_chat.id)][8::])
        mode[str(update.effective_chat.id)] = ""

    elif mode[str(update.effective_chat.id)].startswith("ei"):
        item = int(mode[str(update.effective_chat.id)][2:4])
        listt = get_list(mode[str(update.effective_chat.id)][4::])
        listt["items"][item] = update.message.text
        save_list(listt, mode[str(update.effective_chat.id)][4::])
        edit_list(update, context, listt["id"])
        mode[str(update.effective_chat.id)] = ""

    elif mode[str(update.effective_chat.id)] == "join":
        try:
            listt = get_list(update.message.text)
            listt["requests"].append(update.effective_chat.id)
            save_list(listt, update.message.text)
            context.bot.send_message(text="Request to join was successfully send\nAs soon as list owner accept your request, list will be visible in your lists list",
                                     reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("Back", callback_data="menu")]]), chat_id=update.effective_chat.id)
        except IOError:
            context.bot.send_message(
                text="List with that id don't exitst",
                reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("Back", callback_data="menu")]]),
                chat_id=update.effective_chat.id)
        mode[str(update.effective_chat.id)] = ""

    elif mode[str(update.effective_chat.id)] == "create_list":
        json.dump({
            "name": update.message.text,
            "id": cfg["lists"],
            "admins": [update.effective_chat.id],
            "users": [update.effective_chat.id],
            "requests": [],
            "items": [],
            "completed": [],
        }, open("lists/" + str(cfg["lists"]) + ".json", "w"))
        file["lists_id"].append(cfg["lists"])
        save_user(file, update.effective_chat.id)
        menu(update, context)
        mode[str(update.effective_chat.id)] = ""
        cfg["lists"] += 1
        json.dump(cfg, open("cfg.json", "w"))

    elif mode[str(update.effective_chat.id)].startswith("rename_list"):
        listt = get_list(mode[str(update.effective_chat.id)][11::])
        listt["name"] = update.message.text
        save_list(listt, mode[str(update.effective_chat.id)][11::])
        edit_list(update, context, listt["id"])
        mode[str(update.effective_chat.id)] = ""

    json.dump(mode, open("users/mode.json", "w"))
    context.bot.deleteMessage(chat_id=update.effective_chat.id, message_id=update.message.message_id)

def add_list(update, list):
    file = get_user(update.effective_chat.id)
    file.append(list)
    save_user(file, update.effective_chat.id)

'''
d* - deletes * list
!* - opens * list
ei?* - edits ? item in * list
di?* - deletes ? item in * list
add*_% - adds * user to % list
du*_% - deletes * user from %
ci?* - completes ? item in * list
new_item* - creates new item in * list
edit_item* - opens menu for editing items in * list
delete_item* - opens menu for removing items in * list
rename_list* - renames * list
complete_item* - opens menu for compliting items
requests* - opens join requests for * list
'''

#Reacts on button pressing
def button(update, context):
    mode = json.load(open("users/mode.json", "r"))
    query = update.callback_query
    query.answer()
    file = get_user(update.effective_chat.id)
    if query.data == "start" or query.data == "change_name":
        file["name"] = ""
        save_user(file, update.effective_chat.id)
        query.edit_message_text(text="Enter your name:")
        mode[str(update.effective_chat.id)] = ""

    elif query.data == "menu":
        menu(update, context);
        mode[str(update.effective_chat.id)] = ""

    elif query.data == "create_list":
        query.edit_message_text(text="Enter a new name for your list:")
        mode[str(update.effective_chat.id)] = "create_list"

    elif query.data.startswith("new_item"):
        query.edit_message_text(text="Enter a new item ")
        mode[str(update.effective_chat.id)] = query.data

    elif query.data == "open_list":
        keyboard = []
        for i in file["lists_id"]:
            keyboard.append([InlineKeyboardButton(get_list(str(i))["name"], callback_data="!"+str(i))])
        keyboard.append([InlineKeyboardButton("Back to menu", callback_data="menu")])
        query.edit_message_text(text="Select list", reply_markup=InlineKeyboardMarkup(keyboard))

    elif query.data == "delete_list":
        keyboard = []
        for i in file["lists_id"]:
            filed = get_list(str(i))
            keyboard.append([InlineKeyboardButton(filed["name"], callback_data="d" + str(i))])
        keyboard.append([InlineKeyboardButton("Back to menu", callback_data="menu")])
        query.edit_message_text(text="Select list that you want to delete", reply_markup=InlineKeyboardMarkup(keyboard))

    elif query.data[0] == "!":
        listt = get_list(str(query.data[1::]))
        buffer = ""
        buffer += "\nList id:" + str(listt["id"]) + SEP + "Members:" + str(len(listt["users"])) + SEP
        if len(listt["items"]) != 0:
            for i in listt["items"]:
                buffer += ("\n• " + str(i))
        else:
            buffer += "\n Nothing there"
        if file["user_id"] in listt["admins"]:
            keyboard = [
                [InlineKeyboardButton("Edit list", callback_data="edit_list" + str(listt["id"]))],
                [InlineKeyboardButton("Accept requests", callback_data="requests" + str(listt["id"]))],
                [InlineKeyboardButton("Delete users from list", callback_data="delete_user" + str(listt["id"]))],
                [InlineKeyboardButton("Back to menu", callback_data="menu")]
            ]
            query.edit_message_text(text="\t"+listt["name"]+buffer, reply_markup=InlineKeyboardMarkup(keyboard),
                                    parse_mode=telegram.ParseMode.HTML)
        else:
            query.edit_message_text(text="\t" + listt["name"] + buffer, reply_markup=InlineKeyboardMarkup(keyboards["list"]),
                                    parse_mode=telegram.ParseMode.HTML)

    elif query.data.startswith("edit_list"):
        edit_list(update, context, query.data[9::])

    elif query.data.startswith("edit_item"):
        keyboard = [[]]
        buffer = ""
        listt = get_list(str(query.data[10::]))
        buffer += SEP + "\t\t❗EDITOR MODE❗" + "\n\t<b>Choose an item that you wanna to edit</b>" + SEP
        for i in range(0, len(listt["items"])):
            i += int(query.data[9]) * 12
            if len(listt["items"]) <= i:
                break
            if i < int(query.data[9]) * 12 + 12:
                if i % 3 == 0:
                    keyboard.append([])
                if i < 10:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="ei" + "0" + str(i) + str(listt["id"])))
                else:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="ei" + str(i) + str(listt["id"])))
            else:
                break

        if len(listt["items"]) != 0:
            for i in range(0, len(listt["items"])):
                buffer += ("\n" + str(i + 1) + ". " + str(listt["items"][i]))
        else:
            buffer += "\n Nothing there"
        keyboard.append([])
        if query.data[9] != "0":
            keyboard[-1].append(InlineKeyboardButton("<", callback_data="edit_item" + str(int(query.data[9]) - 1) + str(query.data[10::])))
        keyboard[-1].append(InlineKeyboardButton("back", callback_data="edit_list" + str(query.data[10::])))
        if int(query.data[9]) != len(listt["items"]) // 12 or int(query.data[9]) > 8:
            keyboard[-1].append(InlineKeyboardButton(">", callback_data="edit_item" + str(int(query.data[9]) + 1) + str(query.data[10::])))
        query.edit_message_text(text="\t" + listt["name"] + buffer, reply_markup=InlineKeyboardMarkup(keyboard), parse_mode=telegram.ParseMode.HTML)

    elif query.data.startswith("complete_item"):
        keyboard = [[]]
        buffer = ""
        listt = get_list(str(query.data[14::]))
        buffer += "\t" + SEP + "\t\t❗EDITOR MODE❗" + "\n\t<b>Choose an item that you wanna to complete</b>" + SEP
        for i in range(0, len(listt["items"])):
            if i in listt["completed"]:
                continue
            i += int(query.data[13]) * 12
            if len(listt["items"]) <= i:
                break
            if i < int(query.data[13]) * 12 + 12:
                if i % 3 == 0:
                    keyboard.append([])
                if i < 10:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="ci" + "0" + str(i) + str(listt["id"])))
                else:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="ci" + str(i) + str(listt["id"])))
            else:
                break

        if len(listt["items"]) != 0:
            for i in range(0, len(listt["items"])):
                buffer += ("\n" + str(i + 1) + ". " + str(listt["items"][i]))
        else:
            buffer += "\n Nothing there"
        keyboard.append([])
        if query.data[13] != "0":
            keyboard[-1].append(InlineKeyboardButton("<",
                                                     callback_data="complete_item" + str(int(query.data[13]) - 1) + str(query.data[14::])))
        keyboard[-1].append(InlineKeyboardButton("back", callback_data="edit_list" + str(query.data[14::])))
        if int(query.data[13]) != len(listt["items"]) // 12 or int(query.data[13]) > 8:
            keyboard[-1].append(InlineKeyboardButton(">",
                                                     callback_data="complete_item" + str(int(query.data[13]) + 1) + str(query.data[14::])))
        query.edit_message_text(text="\t" + listt["name"] + buffer, reply_markup=InlineKeyboardMarkup(keyboard),
                                parse_mode=telegram.ParseMode.HTML)

    elif query.data.startswith("ci"):
        item = int(query.data[2:4])
        listt = get_list(query.data[4::])
        listt["items"][item] = "<strike>" + listt["items"][item] + "</strike>"
        listt["completed"].append(item)
        save_list(listt, query.data[4::])
        query.edit_message_text(text="Item completed!", reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("back", callback_data="edit_list" + str(query.data[4::]))]]))

    elif query.data.startswith("delete_item"):
        keyboard = [[]]
        buffer = ""
        listt = get_list(query.data[12::])
        buffer += "\t" + SEP + "\t\t❗EDITOR MODE❗" + "\n\t<b>Choose an item that you wanna to delete</b>" + SEP
        for i in range(0, len(listt["items"])):
            i += int(query.data[11]) * 12
            if len(listt["items"]) <= i:
                break
            if i < int(query.data[11]) * 12 + 12:
                if i % 3 == 0:
                    keyboard.append([])
                if i < 10:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="di" + "0" + str(i) + str(listt["id"])))
                else:
                    keyboard[i % 12 // 3].append(
                        InlineKeyboardButton(str(i + 1), callback_data="di" + str(i) + str(listt["id"])))
            else:
                break

        if len(listt["items"]) != 0:
            for i in range(0, len(listt["items"])):
                buffer += ("\n" + str(i + 1) + ". " + str(listt["items"][i]))
        else:
            buffer += "\n Nothing there"
        keyboard.append([])
        if query.data[11] != "0":
            keyboard[-1].append(InlineKeyboardButton("<", callback_data="delete_item" + str(int(query.data[11]) - 1) + str(query.data[12::])))
        keyboard[-1].append(InlineKeyboardButton("back", callback_data="edit_list" + str(query.data[12::])))
        if int(query.data[11]) != len(listt["items"]) // 12 or int(query.data[11]) > 8:
            keyboard[-1].append(InlineKeyboardButton(">", callback_data="delete_item" + str(int(query.data[11]) + 1) + str(query.data[12::])))
        query.edit_message_text(text="\t" + listt["name"] + buffer, reply_markup=InlineKeyboardMarkup(keyboard), parse_mode=telegram.ParseMode.HTML)

    elif query.data == "join_list":
        query.edit_message_text(text="Enter list id that you wanna to join",
                                reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("back", callback_data="menu")]]))
        mode[str(update.effective_chat.id)] = "join"

    elif query.data.startswith("requests"):
        listt = get_list(query.data[8::])
        keyboard = [[]]
        for i in range(0, len(listt["requests"])):
            if i % 2 == 0:
                keyboard.append([])
            keyboard[i // 2].append(InlineKeyboardButton(get_user(listt["requests"][i])["name"],
                                                         callback_data="add" + str(listt["requests"][i]) + "_" + str(listt["id"])))
        keyboard.append([InlineKeyboardButton("Back", callback_data="!" + query.data[8::])])
        query.edit_message_text(text="Select user that you wanna to add:", reply_markup=InlineKeyboardMarkup(keyboard))

    elif query.data.startswith("add"):
        user_id = query.data[3:query.data.index("_")]
        list_id = query.data[query.data.index("_") + 1::]
        listt = get_list(list_id)
        user = get_user(user_id)
        if user_id not in listt["users"]:
            listt["users"].append(int(user_id))
            listt["requests"].pop(listt["requests"].index(int(user_id)))
            user["lists_id"].append(int(list_id))
            save_list(listt, list_id)
            save_user(user, user_id)
            context.bot.send_message(text="Your request has been accepted", chat_id=user_id,
                                     reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("Go to that list", callback_data="!"+str(list_id))]]))
            query.edit_message_text(text="User was successfully added to your list",
                                    reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("Back", callback_data="edit_list" + str(list_id))]]))
        else:
            listt["requests"].pop(listt["requests"].index(int(user_id)))
            save_list(listt, list_id)
            query.edit_message_text(text="This user is already added into this list",
                                    reply_markup=InlineKeyboardMarkup([[InlineKeyboardButton("Back", callback_data="edit_list" + str(list_id))]]))

    elif query.data.startswith("delete_user"):
        listt = get_list(query.data[11::])
        keyboard = []
        for i in range(0, len(listt["users"])):
            if listt["users"][i] != file["user_id"]:
                user = get_user(listt["users"][i])
                keyboard.append([InlineKeyboardButton(user["name"], callback_data="du" + str(user["user_id"]) + "_" + str(listt["id"]))])
        keyboard.append([InlineKeyboardButton("Back", callback_data="!" + query.data[11::])])
        query.edit_message_text(text="Select user that you want to delete", reply_markup=InlineKeyboardMarkup(keyboard))

    elif query.data.startswith("du"):
        user_id = query.data[2:query.data.index("_")]
        list_id = query.data[query.data.index("_") + 1::]
        listt = get_list(list_id)
        user = get_user(user_id)
        listt["users"].pop(listt["users"].index(user_id))
        user["lists"].pop(user["lists"].index(list_id))
        save_list(listt, list_id)
        save_user(user, user_id)
        query.edit_message_text(text="User successfully deleted!",
                                reply_markup=InlineKeyboardMarkup([InlineKeyboardButton("Back", callback_data="!" + query.data[11::])]))

    elif query.data.startswith("rename_list"):
        query.edit_message_text(text="Enter a new name for your list:")
        mode[str(update.effective_chat.id)] = "rename_list" + str(query.data[11::])

    elif query.data.startswith("ei"):
        query.edit_message_text(text="Enter modified item:")
        mode[str(update.effective_chat.id)] = query.data

    elif query.data.startswith("di"):
        keyboard = [[InlineKeyboardButton("Back", callback_data="edit_list" + str(query.data[4::]))]]
        listt = get_list(query.data[4::])
        query.edit_message_text(text="Item successfully deleted!", reply_markup=InlineKeyboardMarkup(keyboard))
        listt["items"].pop(int(query.data[2:4]))
        save_list(listt, query.data[4::])

    elif query.data.startswith("d"):
        listt = get_list(query.data[1::])
        if file["user_id"] in listt["admins"]:
            os.remove("lists/"+query.data[1::]+".json")
        else:
            listt["users"].pop(listt["users"].index(file["user_id"]))
            save_list(listt, query.data[1::])
        query.edit_message_text(text="List successfully deleted!", reply_markup=InlineKeyboardMarkup(keyboards["list"]))
        for i in range(0, len(file["lists_id"])):
            if file["lists_id"][i] == int(query.data[1::]):
                file["lists_id"].pop(i)

    json.dump(mode, open("users/mode.json", "w"))
    save_user(file, update.effective_chat.id)

if __name__ == "__main__":
    start_handle = CommandHandler('start', start)
    disp.add_handler(CallbackQueryHandler(button))
    disp.add_handler(MessageHandler(Filters.text & ~Filters.command, get_message))
    disp.add_handler(start_handle)
    updater.start_polling()